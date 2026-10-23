<?php

namespace ZetthCore\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ActivityLog implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Header request yang layak disimpan untuk audit. Sisanya dibuang:
     * cookie & authorization/php-auth-* berisi token/password, sedangkan
     * cf-*, host, connection, accept-encoding hanya duplikat/derau
     * (terukur: 75% byte headers berasal dari key yang tak dibutuhkan).
     */
    public const KEPT_HEADERS = [
        'user-agent',
        'referer',
        'accept',
        'accept-language',
        'content-type',
        'x-requested-with',
        'origin',
        'sec-ch-ua',
    ];

    /**
     * Saring header request menjadi key yang diizinkan saja (case-insensitive).
     */
    public static function filterHeaders($headers): array
    {
        $kept = array_flip(self::KEPT_HEADERS);
        $result = [];

        foreach ((array) $headers as $key => $value) {
            $lower = strtolower((string) $key);

            if (isset($kept[$lower])) {
                $result[$lower] = $value;
            }
        }

        return $result;
    }

    protected $description;
    protected $user;
    protected $req;
    protected $serv;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($description, $user, $req, $serv)
    {
        $this->description = $description;
        $this->user = $user;
        $this->req = $req;
        $this->serv = $serv;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $act = new \ZetthCore\Models\ActivityLog;
        $act->description = $this->description;
        $act->method = $this->req['method'];
        $act->path = $this->req['path'] ?? '-';
        $act->ip = getUserIP($this->serv);
        $act->headers = json_encode(self::filterHeaders($this->req['header'] ?? []));
        $act->get = json_encode($this->req['query']);
        $act->post = json_encode($this->req['post']);
        $act->files = json_encode($this->req['files']);
        $act->user_id = $this->user->id ?? (app('user')->id ?? null);
        $site = \app('site');
        if (!$site && !empty($this->serv['HTTP_HOST'])) {
            $host = strtolower($this->serv['HTTP_HOST']);
            $site = \App\Models\Site::where('domain', $host)->first()
                ?? \App\Models\Site::where('domain', preg_replace('/^[^.]+\./', '', $host))->first();
        }
        $act->site_id = $site?->id ?? 1;
        $act->save();
    }
}

<?php
namespace ZetthCore\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use ZetthCore\Http\Controllers\AdminController;
use ZetthCore\Models\Post;
use ZetthCore\Models\PostComment;
use ZetthCore\Models\Term;
use ZetthCore\Models\VisitorLog;

class AjaxController extends AdminController
{
    public function term($term)
    {
        $arr = [];
        if ($term == "tags") {
            $type = "tag";
        } else if ($term == "categories") {
            $type = "category";
        } else {
            abort(404);
        }

        $data = Term::select('name')->
            where('type', $type)->
            where('status', 1)->
            get();

        foreach ($data as $value) {
            $arr[] = $value->name;
        }

        return response()->json($arr);
    }

    public function pageview(Request $r)
    {
        $data_visit = [];
        $res = [
            'rows' => [
                [
                    'name' => 'Kunjungan',
                    'data' => [],
                    'color' => 'coral',
                ],
                [
                    'name' => 'Pengunjung Unik',
                    'data' => [],
                    'color' => 'grey',
                ],
            ],
            'status' => false,
        ];

        /* set variable */
        $start_date = $r->input('start');
        $end_date = $r->input('end');
        $range = $r->input('range');

        /* set start and end as carbon */
        $start = carbon_query($start_date . ' 00:00:00');
        $end = carbon_query($end_date . ' 23:59:59');
        // dd($start, $end, $start_date, $end_date);

        switch ($range) {
            case 'hourly':
                $df = '%Y-%m-%d %H';
                break;
            case 'monthly':
                $df = '%Y-%m';
                break;
            case 'yearly':
                $df = '%Y';
                break;
            case 'daily':
                $df = '%Y-%m-%d';
                break;
            default:
                return response()->json($res);
                break;
        }

        /* disable strict */
        config()->set('database.connections.mysql.strict', false);
        \DB::reconnect();

        /* query */
        $visits = VisitorLog::select('created_at', 'ip', DB::raw('date_format(created_at, \'' . $df . '\') as created'), DB::raw('sum(count) as count'))
            ->where('is_robot', 0)
            ->whereBetween('created_at', [$start, $end])
            ->orderBy('created_at', 'ASC')
            ->groupBy(DB::raw('date_format(created_at, \'' . $df . '\')'), 'ip')
            ->get();

        /* enable strict */
        config()->set('database.connections.mysql.strict', true);
        \DB::reconnect();

        if ($visits) {
            switch ($range) {
                case 'hourly':
                    $arr_diff = $this->pageview_hourly($visits, $df);
                    break;
                case 'monthly':
                    $arr_diff = $this->pageview_monthly($visits, $start, $df);
                    break;
                case 'yearly':
                    $arr_diff = $this->pageview_yearly($visits, $start, $df);
                    break;
                case 'daily':
                    $arr_diff = $this->pageview_daily($visits, $start, $df);
                    break;
                default:
                    return response()->json($res);
                    break;
            }

            if (!empty($arr_diff)) {
                foreach ($arr_diff as $k => $v) {
                    $data_visit[$v]['visit'] = 0;
                    $data_visit[$v]['ip'] = 0;
                }
            }

            $time2 = '';
            foreach ($visits as $k => $v) {
                $created = carbon($v->created_at)->format(str_replace("%", "", $df));
                if ($time2 != $created) {
                    $data_visit[$created]['visit'] = $v->count;
                    $data_visit[$created]['ip'] = 1;
                } else {
                    $data_visit[$created]['visit'] = $v->count + $data_visit[$created]['visit'];
                    $data_visit[$created]['ip'] = $data_visit[$created]['ip'] + 1;
                }

                $time2 = $created;
                $ip = $v->ip;
            }

            $res['status'] = true;

            foreach (array_sort_recursive($data_visit) as $k => $v) {
                $res['rows'][0]['data'][] = (int) $v['visit'];
                $res['rows'][1]['data'][] = (int) $v['ip'];
            }
        }

        return response()->json($res);
    }

    public function pageview_hourly($visits = [], $format = 'Y-m-d H')
    {
        $time = '';
        $timee = '';
        $time_exist = [];
        $time_from_max = [];
        $arr_diff = [];

        if (empty($visits)) {
            return false;
        }

        foreach ($visits as $k => $v) {
            $created = carbon($v->created_at)->format(str_replace("%", "", $format));
            if ($time != $created) {
                $time_exist[] = $created;
            }

            $time = $created;
            $timee = carbon($v->created_at);
        }

        $max = substr($timee, -8, 2);
        for ($i = 0; $i <= $max; $i++) {
            $h = str_pad($i, 2, "0", STR_PAD_LEFT);
            $time_from_max[] = date("Y-m-d", strtotime($timee)) . " " . $h;
        }

        $arr_diff = array_diff($time_from_max, $time_exist);

        return $arr_diff;
    }

    public function pageview_daily($visits = [], $start, $format = 'Y-m-d H')
    {
        $time = '';
        $timee = '';
        $time_exist = [];
        $time_from_max = [];
        $arr_diff = [];

        if (empty($visits)) {
            return false;
        }

        foreach ($visits as $k => $v) {
            $created = carbon($v->created_at)->format(str_replace("%", "", $format));
            if ($time != $created) {
                $time_exist[] = $created;
            }

            $time = $created;
            $timee = carbon($v->created_at);
        }

        $st = date("d", strtotime($start));
        $max = date("d", strtotime($timee));
        for ($i = $st; $i <= $max; $i++) {
            $h = str_pad($i, 2, "0", STR_PAD_LEFT);
            $time_from_max[] = date("Y-m-", strtotime($timee)) . $h;
        }

        $arr_diff = array_diff($time_from_max, $time_exist);

        return $arr_diff;
    }

    public function pageview_monthly($visits = [], $start, $format = 'Y-m-d H')
    {
        $time = '';
        $timee = '';
        $time_exist = [];
        $time_from_max = [];
        $arr_diff = [];

        if (empty($visits)) {
            return false;
        }

        foreach ($visits as $k => $v) {
            $created = carbon($v->created_at)->format(str_replace("%", "", $format));
            if ($time != $created) {
                $time_exist[] = $created;
            }

            $time = $created;
            $timee = carbon($v->created_at);
        }

        $st = date("m", strtotime($start));
        $max = date("m", strtotime($timee));
        for ($i = $st; $i <= $max; $i++) {
            $h = str_pad($i, 2, "0", STR_PAD_LEFT);
            $time_from_max[] = date("Y-", strtotime($timee)) . $h;
        }

        $arr_diff = array_diff($time_from_max, $time_exist);

        return $arr_diff;
    }

    public function pageview_yearly($visits = [], $start, $format = 'Y-m-d H')
    {
        $time = '';
        $timee = '';
        $time_exist = [];
        $time_from_max = [];
        $arr_diff = [];

        if (empty($visits)) {
            return false;
        }

        foreach ($visits as $k => $v) {
            $created = carbon($v->created_at)->format(str_replace("%", "", $format));
            if ($time != $created) {
                $time_exist[] = $created;
            }

            $time = $created;
            $timee = carbon($v->created_at);
        }

        $st = date("Y", strtotime($start));
        $max = date("Y", strtotime($timee));
        for ($i = $st; $i <= $max; $i++) {
            $time_from_max[] = date("Y", strtotime($timee));
        }

        $arr_diff = array_diff($time_from_max, $time_exist);

        return $arr_diff;
    }

    public function popularpost(Request $r)
    {
        $res = [
            'rows' => [],
            'status' => false,
        ];

        /* set start and end date */
        $start = $r->input('start');
        $end = $r->input('end');

        /* get posts */
        $posts = _getPopularPosts(5, $start, $end);

        if ($posts) {
            foreach ($posts as $k => $v) {
                $post = $v->post;
                $cat = [];
                $res['rows'][$k]['title'] = app('is_desktop') ? str_limit($post->title, 80) : str_limit($post->title, 30);
                $res['rows'][$k]['slug'] = $post->slug;
                $res['rows'][$k]['views'] = (int) $v->count;
                if (count($post->categories) > 0) {
                    foreach ($post->categories as $key => $val) {
                        $cat[] = '<a style="text-decoration:none;">' . $val->name . '</a>';
                    }
                }
                $res['rows'][$k]['categories'] = implode(", ", $cat);
            }

            $res['status'] = true;
        }

        return response()->json($res);
    }

    public function recentcomment(Request $r)
    {
        $res = [
            'rows' => [],
            'status' => false,
        ];

        $start = $r->input('start');
        $end = $r->input('end');

        $comms = PostComment::where('created_by', 0)
            ->orderBy('created_at', 'DESC')
            ->with('post')
            ->take(5)
            ->get();

        if ($comms) {
            foreach ($comms as $k => $v) {
                $today = date("Y-m-d");
                $post = app('is_desktop') ? str_limit($v->post->title, 60) : str_limit($v->post->title, 20);
                $res['rows'][$k]['id'] = $v->comment_id;
                $res['rows'][$k]['text'] = app('is_desktop') ? str_limit(strip_tags($v->comment_text), 75) : str_limit(strip_tags($v->comment_text), 20);
                $res['rows'][$k]['name'] = $v->comment_name;
                $res['rows'][$k]['post'] = '<a style="text-decoration:none;">' . $post . '</a>';
                $res['rows'][$k]['time'] = str_replace($today, "", $v->created_at);
            }

            $res['status'] = true;
        }

        return response()->json($res);
    }
}

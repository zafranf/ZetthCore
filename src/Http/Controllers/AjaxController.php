<?php
namespace ZetthCore\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use ZetthCore\Http\Controllers\Controller;
use ZetthCore\Models\Post;
use ZetthCore\Models\PostComment;
use ZetthCore\Models\Term;
use ZetthCore\Models\VisitorLog;

class AjaxController extends Controller
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

        $data = Term::select('display_name')->
            where('type', $type)->
            where('status', 1)->
            get();

        foreach ($data as $value) {
            $arr[] = $value->display_name;
        }

        return response()->json($arr);
    }

    public function pageview(Request $r)
    {
        $data_visit = [];
        $res = [
            'rows' => [
                [
                    'name' => 'Visits',
                    'data' => [],
                    'color' => 'coral',
                ],
                [
                    'name' => 'Unique Visitors',
                    'data' => [],
                    'color' => 'grey',
                ],
            ],
            'status' => false,
        ];

        $start = $r->input('start');
        $end = $r->input('end');
        $range = $r->input('range');

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

        $visits = VisitorLog::select('ip', DB::raw('date_format(created_at, \'' . $df . '\') as created'), DB::raw('sum(count) as count'))
            ->where('is_robot', 0)
            ->whereBetween('created_at', [$start . ' 00:00:00', $end . ' 23:59:59'])
            ->orderBy('created_at', 'ASC')
            ->groupBy(DB::raw('date_format(created_at, \'' . $df . '\')'), 'ip')
            ->get();

        if ($visits) {
            switch ($range) {
                case 'hourly':
                    $arr_diff = $this->pageview_hourly($visits);
                    break;
                case 'monthly':
                    $arr_diff = $this->pageview_monthly($visits, $start);
                    break;
                case 'yearly':
                    $arr_diff = $this->pageview_yearly($visits, $start);
                    break;
                case 'daily':
                    $arr_diff = $this->pageview_daily($visits, $start);
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
                if ($time2 != $v->created) {
                    $data_visit[$v->created]['visit'] = $v->count;
                    $data_visit[$v->created]['ip'] = 1;
                } else {
                    $data_visit[$v->created]['visit'] = $v->count + $data_visit[$v->created]['visit'];
                    $data_visit[$v->created]['ip'] = $data_visit[$v->created]['ip'] + 1;
                }

                $time2 = $v->created;
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

    public function pageview_hourly($visits = [])
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
            if ($time != $v->created) {
                $time_exist[] = $v->created;
            }

            $time = $v->created;
            $timee = $v->created_at;
        }

        $max = substr($timee, -8, 2);
        for ($i = 0; $i <= $max; $i++) {
            $h = str_pad($i, 2, "0", STR_PAD_LEFT);
            $time_from_max[] = date("Y-m-d", strtotime($timee)) . " " . $h;
        }

        $arr_diff = array_diff($time_from_max, $time_exist);

        return $arr_diff;
    }

    public function pageview_daily($visits = [], $start)
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
            if ($time != $v->created) {
                $time_exist[] = $v->created;
            }

            $time = $v->created;
            $timee = $v->created_at;
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

    public function pageview_monthly($visits = [], $start)
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
            if ($time != $v->created) {
                $time_exist[] = $v->created;
            }

            $time = $v->created;
            $timee = $v->created_at;
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

    public function pageview_yearly($visits = [], $start)
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
            if ($time != $v->created) {
                $time_exist[] = $v->created;
            }

            $time = $v->created;
            $timee = $v->created_at;
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

        $start = $r->input('start');
        $end = $r->input('end');

        $pops = Post::select('id', 'title', 'slug', 'visited')
            ->where('status', 1)
            ->where('type', 'article')
            ->whereBetween('updated_at', [$start . ' 00:00:00', $end . ' 23:59:59'])
            ->orderBy('visited', 'DESC')
            ->with('categories')
            ->take(5)
            ->get();

        if ($pops) {
            foreach ($pops as $k => $v) {
                $cat = [];
                $res['rows'][$k]['title'] = isDesktop() ? str_limit($v->title, 80) : str_limit($v->title, 30);
                $res['rows'][$k]['slug'] = $v->slug;
                $res['rows'][$k]['views'] = $v->visited;
                if (count($v->categories) > 0) {
                    foreach ($v->categories as $key => $val) {
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
                $post = isDesktop() ? str_limit($v->post->title, 60) : str_limit($v->post->title, 20);
                $res['rows'][$k]['id'] = $v->comment_id;
                $res['rows'][$k]['text'] = isDesktop() ? str_limit(strip_tags($v->comment_text), 75) : str_limit(strip_tags($v->comment_text), 20);
                $res['rows'][$k]['name'] = $v->comment_name;
                $res['rows'][$k]['post'] = '<a style="text-decoration:none;">' . $post . '</a>';
                $res['rows'][$k]['time'] = str_replace($today, "", $v->created_at);
            }

            $res['status'] = true;
        }

        return response()->json($res);
    }
}

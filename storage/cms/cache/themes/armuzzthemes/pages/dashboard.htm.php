<?php 
use Illuminate\Support\Facades\Schema;
class Cms698d67953e413628419163_6e0b0e377dfafb60d07ff8de4fa388f6Class extends Cms\Classes\PageCode
{
public function onStart()
{
    $this['teachersReady'] = Schema::hasTable('latihan_teachers');

    if (!$this['teachersReady']) {
        $this['teacherStats'] = [
            'total' => 0,
            'active' => 0,
            'inactive' => 0,
        ];
        $this['recentTeachers'] = [];
        $this['subjectData'] = [];
        $this['timelineData'] = [];
        return;
    }

    $total = Db::table('latihan_teachers')->count();
    $active = Db::table('latihan_teachers')->where('is_active', 1)->count();
    $inactive = max(0, $total - $active);

    $this['teacherStats'] = compact('total', 'active', 'inactive');
    $this['recentTeachers'] = Db::table('latihan_teachers')->orderByDesc('id')->limit(5)->get();

    // Data for Subject Distribution chart (pie/doughnut)
    $subjects = Db::table('latihan_teachers')
        ->select(Db::raw('subject, COUNT(*) as cnt'))
        ->groupBy('subject')
        ->orderByDesc('cnt')
        ->limit(8)
        ->get();
    $this['subjectData'] = $subjects;

    // Data for Timeline chart (teachers created per day, last 7 days)
    $timeline = Db::table('latihan_teachers')
        ->select(Db::raw("DATE(created_at) as day, COUNT(*) as cnt"))
        ->where('created_at', '>=', date('Y-m-d', strtotime('-6 days')))
        ->groupBy('day')
        ->orderBy('day')
        ->get();
    $this['timelineData'] = $timeline;
}
}

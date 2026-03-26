<?php 
use Illuminate\Support\Facades\Schema;
class Cms69c4a0e2601b1570401586_0159136321384031ac3d9be3aa60c323Class extends Cms\Classes\PageCode
{
public function onStart()
{
    $this['teachersReady'] = Schema::hasTable('latihan_teachers');
    $this['studentsReady'] = Schema::hasTable('latihan_students');

    $this['teacherStats'] = ['total' => 0, 'active' => 0, 'inactive' => 0];
    $this['studentStats'] = ['total' => 0, 'active' => 0, 'inactive' => 0];
    $this['recentTeachers'] = [];
    $this['subjectData'] = [];
    $this['timelineData'] = [];

    if ($this['teachersReady']) {
        $totalT = Db::table('latihan_teachers')->count();
        $activeT = Db::table('latihan_teachers')->where('is_active', 1)->count();
        $this['teacherStats'] = [
            'total' => $totalT,
            'active' => $activeT,
            'inactive' => max(0, $totalT - $activeT),
        ];

        $this['recentTeachers'] = Db::table('latihan_teachers')->orderByDesc('id')->limit(5)->get();

        $this['subjectData'] = Db::table('latihan_teachers')
            ->select(Db::raw('subject, COUNT(*) as cnt'))
            ->groupBy('subject')
            ->orderByDesc('cnt')
            ->limit(8)
            ->get();

        $this['timelineData'] = Db::table('latihan_teachers')
            ->select(Db::raw("DATE(created_at) as day, COUNT(*) as cnt"))
            ->where('created_at', '>=', date('Y-m-d', strtotime('-6 days')))
            ->groupBy('day')
            ->orderBy('day')
            ->get();
    }

    if ($this['studentsReady']) {
        $totalS = Db::table('latihan_students')->count();
        $activeS = Db::table('latihan_students')->where('is_active', 1)->count();
        $this['studentStats'] = [
            'total' => $totalS,
            'active' => $activeS,
            'inactive' => max(0, $totalS - $activeS),
        ];
    }
}
}

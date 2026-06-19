import subprocess
code = "foreach(DB::table('packages')->get() as $p) { if(!empty($p->sightseeing_list) && $p->sightseeing_list != '[]') { echo $p->title . ' -> ' . $p->sightseeing_list . PHP_EOL; } }"
subprocess.run(['php', 'artisan', 'tinker', '--execute', code])

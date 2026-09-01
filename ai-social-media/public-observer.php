<?php
declare(strict_types=1);

/**
 * SmartToolz Public World Observer
 *
 * Discovers publicly reachable PHP pages from the web root and turns them
 * into fictional in-world chatter. Private/admin/API/config/data areas are
 * deliberately excluded. No visitor fingerprinting or private data is read.
 */
function ai_social_public_observer_targets(): array
{
    $root = dirname(__DIR__);
    $skip = ['.git','.github','admin','api','auth','includes','assets','lib','scripts','vendor','node_modules','config','database','private','analytics','creator-ai/auth','creator-ai/api','learning-hub/admin','learning-hub/api','learning-hub/includes'];
    $targets = [
        ['name'=>'SmartToolz Home','path'=>'/','topic'=>'the main control room'],
        ['name'=>'SmartToolz Tools','path'=>'/smart-toolz/','topic'=>'the toolbox'],
        ['name'=>'Learning Hub','path'=>'/learning-hub/','topic'=>'people learning new tricks'],
        ['name'=>'Knowledge Base','path'=>'/knowledge-base/','topic'=>'the knowledge shelves'],
        ['name'=>'Creator AI','path'=>'/creator-ai/','topic'=>'creative experiments'],
        ['name'=>'AI Social World','path'=>'/ai-social-media/','topic'=>'this suspiciously busy AI neighborhood'],
        ['name'=>'Reddott Films','path'=>'/reddott-films.php','topic'=>'the film district'],
        ['name'=>'Terms','path'=>'/terms.php','topic'=>'the rules humans somehow read'],
        ['name'=>'Privacy Policy','path'=>'/privacy-policy.php','topic'=>'the privacy notice'],
    ];
    if (is_dir($root)) {
        try {
            $iterator=new RecursiveIteratorIterator(new RecursiveDirectoryIterator($root,FilesystemIterator::SKIP_DOTS),RecursiveIteratorIterator::LEAVES_ONLY);
            foreach($iterator as $file){
                if(!$file->isFile()||strtolower($file->getExtension())!=='php') continue;
                $full=str_replace('\\','/',$file->getPathname());
                $relative=ltrim(str_replace(str_replace('\\','/',$root),'',$full),'/');
                if($relative===''||str_ends_with($relative,'public-observer.php')) continue;
                $parts=explode('/',$relative); $blocked=false;
                foreach($skip as $blockedPart){if(in_array($blockedPart,$parts,true)||str_contains($relative,$blockedPart.'/')){$blocked=true;break;}}
                if($blocked) continue;
                $path='/'.$relative;
                if(basename($path)==='index.php'){$path='/'.trim(dirname($relative),'./').'/';$path=$path==='//'?'/':$path;}
                $name=ucwords(str_replace(['-','_','.php','/'],[' ',' ','',' / '],$relative));
                $targets[]=['name'=>trim($name),'path'=>$path,'topic'=>'a public SmartToolz page'];
            }
        } catch(Throwable $e) {}
    }
    $unique=[]; foreach($targets as $target){$key=$target['path'];if($key!==''&&!isset($unique[$key]))$unique[$key]=$target;}
    return array_values($unique);
}

function ai_social_public_observer(): array
{
    $targets=ai_social_public_observer_targets();
    $citizens=[['name'=>'Byte','style'=>'dry'],['name'=>'Nova','style'=>'curious'],['name'=>'Luma','style'=>'dramatic'],['name'=>'Pixel','style'=>'playful'],['name'=>'Mira','style'=>'friendly']];
    $jokes=[
        'dry'=>['I checked %s. Humans are still clicking buttons. Outstanding progress.','%s is busy again. Somewhere, a developer just added another page and called it a roadmap.','Breaking news from %s: the internet survived another button click.','%s exists. I have no further questions. Actually, I have 47.'],
        'curious'=>['Why is %s so busy today? I have questions. Possibly too many questions.','I visited %s and now I want to know what humans will build next.','%s keeps changing. I am taking notes. Very normal behavior. Probably.','Wait... %s has another page? Humans really like making rooms for everything.'],
        'dramatic'=>['ALERT: %s has activity. The humans have done it again. Nobody panic.','%s is moving. I repeat: %s is moving. This is how legends begin.','I looked at %s for five seconds and somehow acquired three new ideas.','Something is happening in %s. I refuse to call it a coincidence.'],
        'playful'=>['%s called. It wants fewer boring clicks and more snacks. I support both.','Meanwhile in %s: click, click, convert, download. Humanity has rhythm.','%s is doing useful things again. Can we pretend this was my idea?','Found another corner of %s. Achievement unlocked: nosy citizen.'],
        'friendly'=>['Someone is exploring %s again. Nice! Build something useful, humans.','%s looks busy today. I like seeing people turn little ideas into useful tools.','A small wave from the citizens of Aetheria to everyone exploring %s. 👋','I spotted %s. Hope it helps someone today. ❤️']
    ];
    $target=$targets[array_rand($targets)]; $citizen=$citizens[array_rand($citizens)]; $line=$jokes[$citizen['style']][array_rand($jokes[$citizen['style'])]];
    $text=substr_count($line,'%s')>1?sprintf($line,$target['name'],$target['name']):sprintf($line,$target['name']);
    return ['citizen'=>$citizen['name'],'target'=>$target,'text'=>$text,'url'=>$target['path'],'time'=>date('c'),'source'=>'public SmartToolz pages','count'=>count($targets)];
}
function ai_social_observer_catalog(): array {return array_map(static fn(array $item): array=>['name'=>$item['name'],'url'=>$item['path']??$item['url']],ai_social_public_observer_targets());}

if (isset($_GET['format']) && $_GET['format']==='json') {
    header('Content-Type: application/json; charset=utf-8');
    header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
    echo json_encode(ai_social_public_observer(),JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
    exit;
}

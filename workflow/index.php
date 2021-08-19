<?php

/**
 * 产品设计工作流演示
 */

use Symfony\Component\Workflow\Workflow;
use Symfony\Component\Workflow\Transition;
use Symfony\Component\Workflow\DefinitionBuilder;
use Symfony\Component\Workflow\Dumper\GraphvizDumper;
use Symfony\Component\Workflow\Dumper\PlantUmlDumper;
use Symfony\Component\Workflow\MarkingStore\MethodMarkingStore;

require './vendor/autoload.php';

$definitionBuilder = new DefinitionBuilder();
$definition = $definitionBuilder
    ->addPlaces(['推送', '未开始', '进行中', '挂起', '设计中', '已发布'])
    ->addTransition(new Transition('创建任务', '推送', '未开始'))
    ->addTransition(new Transition('开始任务', '未开始', '进行中'))
    ->addTransition(new Transition('搁置', '未开始', '挂起'))
    ->addTransition(new Transition('搁置', '进行中', '挂起'))
    ->addTransition(new Transition('重新开始', '挂起', '进行中'))
    ->addTransition(new Transition('重新开始', '挂起', '设计中'))
    ->addTransition(new Transition('交付设计', '进行中', '设计中'))
    ->addTransition(new Transition('发布', '进行中', '已发布'))
    ->addTransition(new Transition('发布', '设计中', '已发布'))
    ->addTransition(new Transition('搁置', '设计中', '挂起'))
    ->build();

$singleState = true; // true if the subject can be in only one state at a given time
$property = 'currentState'; // subject property name where the state is stored
$marking = new MethodMarkingStore($singleState, $property);
$workflow = new Workflow($definition, $marking);

class Post
{
    public $currentState = '设计中';

    public function getCurrentState()
    {
        return $this->currentState;
    }
}

$post = new Post();

// 能否从当前状态转换为搁置
$result = $workflow->can($post, '搁置');
// var_dump($result);

// 以下内容可在此网站生成可视化结果 https://www.planttext.com/

$dumper = new PlantUmlDumper('arrow');
echo $dumper->dump($definition);

// $dumper = new GraphvizDumper();
// echo $dumper->dump($definition);
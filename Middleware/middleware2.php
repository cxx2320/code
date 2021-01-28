<?php

interface Milldeware
{
    public function process($request, dis $handler);
}

class mid1 implements Milldeware
{
    public function process($request, $handler)
    {
        var_dump(1);
        $response = $handler->handle($request);
        var_dump(6);

        return $response;
    }
}

class mid2 implements Milldeware
{
    public function process($request, $handler)
    {
        var_dump(2);
        $response = $handler->handle($request);
        var_dump(5);

        return $response;
    }
}

class mid3 implements Milldeware
{
    public function process($request, $handler)
    {
        var_dump(3);
        $response = $handler->handle($request);
        var_dump(4);

        return $response;
    }
}

/**
 * 核心中间件.
 */
class core
{
    public $name = 'cxx';

    /**
     * 在核心中间件中需要把请求调度到具体的控制器方法中.
     */
    public function process($request, $handler)
    {
        return $this->name;
    }
}

/**
 * 中间件调度器.
 */
class dis
{
    public $offset = 0;

    // 定义三个全局中间件(按顺序执行)
    public $middlewares = [
        mid1::class,
        mid2::class,
        mid3::class,
    ];

    public function handle($request)
    {
        if (!isset($this->middlewares[$this->offset])) {
            $handler = $request;
        } else {
            $handler = new $this->middlewares[$this->offset]();
        }

        return $handler->process($request, $this->next());
    }

    protected function next(): self
    {
        $this->offset++;

        return $this;
    }
}

$dis = new dis();
$dis->handle(new core());

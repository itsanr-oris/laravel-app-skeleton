<?php
/**
 * Created by PhpStorm.
 * User: f-oris
 * Date: 2019/7/26
 * Time: 10:41 AM
 */

namespace Foris\LaExtension\Http;

use Foris\LaExtension\Services\CrudService;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

/**
 * Class CrudController
 * @package Foris\LaExtension\Controllers
 * @author  f-oris <us@f-oris.me>
 * @version 1.0.0
 */
abstract class CrudController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * 获取资源管理服务
     *
     * @return CrudService
     */
    abstract public function service() : CrudService;

    /**
     * 获取数据列表
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        return Response::success($this->service()->list(request()->query()));
    }

    /**
     * 获取数据详情
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        if ($detail = $this->service()->detail($id)) {
            return Response::success($detail);
        }

        return Response::notFound('获取详情失败，找不到指定资源信息!');
    }

    /**
     * 获取资源创建表单
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function create()
    {
        return Response::success(['form' => []]);
    }

    /**
     * 创建资源信息
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        if ($result = $this->service()->create($request->post())) {
            return Response::success($result);
        }

        return Response::failure('创建资源信息失败，请稍后重新尝试!');
    }

    /**
     * 获取资源编辑表单
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit($id)
    {
        $detail = $this->service()->detail($id);
        return Response::success(['form' => [], 'data' => $detail ?? []]);
    }

    /**
     * 更新资源信息
     *
     * @param Request $request
     * @param         $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        if ($result = $this->service()->update($id, $request->post())) {
            return Response::success($result);
        }

        return Response::failure('更新资源信息失败，请稍后重新尝试!');
    }

    /**
     * 删除资源信息
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        return $this->service()->delete($id) ? Response::success([]) : Response::failure();
    }

    /**
     * 批量删除
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function batchDestroy()
    {
        $ids = request()->input('ids', []);
        return $this->service()->batchDelete($ids) ? Response::success([]) : Response::failure();
    }
}
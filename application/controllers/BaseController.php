<?php
/**
 * Created by PhpStorm.
 * User: zongjun.lan
 * Date: 2020/8/31
 * Time: 3:52 PM
 */

class BaseController extends CI_Controller
{
    /**
     * @var object 用户
     */
    public $user;

    /**
     * @var int 店铺
     */
    public $shop_id;

    /**
     * @var int 用户ID
     */
    public $user_id;

    public function __construct()
    {

        parent::__construct();

        $this->load->library('session');
        // 没有session需要重定向登录
        if (empty($this->session->s_user)) {
            redirect('admin/index');
        }

        $this->user = $this->session->s_user;

        $this->shop_id = $this->session->s_user->u_shop_id;

        $this->user_id = $this->session->s_user->u_id;
    }

    public function getPostData()
    {
        return $this->input->post();
    }

    public function getGetData()
    {
        return $this->input->get();
    }
}
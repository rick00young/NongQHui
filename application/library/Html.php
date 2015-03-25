<?php
/**
 * @describe:
 * @author: Jerry Yang(hy0kle@gmail.com)
 * */
class Html
{
    const page_name = 'cpage';
    const page_size = 5;

    /**
     * total: 记录总数
     * page_size: 每页显示条数
     * page: 当前页数
     * */
    public static function createPager($page, $page_size, $total)
    {
        $page_name = self::page_name;
        $html = '';

        $total      = (int)$total;
        $page       = (int)$page;
        $page_size  = (int)$page_size;
        if ($page < 0 || $page_size < 1 || $total < 1)
        {
            return $html;
        }

        $uri   = isset($_SERVER['QUERY_STRING']) ? $_SERVER['QUERY_STRING'] : '';
        parse_str($uri, $p_uri);
        if (isset($p_uri['v']))
        {
            unset($p_uri['v']);
        }
        if (isset($p_uri[$page_name]))
        {
            unset($p_uri[$page_name]);
        }
        $url = '?v=v1.0&' . http_build_query($p_uri) . '&' . $page_name . '=';

        $page_count = ceil($total / $page_size);

        $html .= '<div class="page">';

        if ($page > 1 && $page_count > 10)
        {
            $html .= sprintf('<a class="prev-page" href="%s">%s</a>', $url . '1', '首页');
        }

        $start = 1;
        $end   = 11;
        $fix_size = 5;

        $sp_size = $page_count - 2 * $fix_size;
        if ($page > $fix_size && $page + $fix_size < $page_count)
        {
            $start = $page - $fix_size;
            $end   = $page + $fix_size;
        }
        elseif ($sp_size > 0 && $page > $sp_size)
        {
            $start = $page_count - 2 * $fix_size + 1;
            $end   = $page_count + 1;
        }
        //echo $start . ' | ' . $end;

        for ($i = $start; $i < $end && $i <= $page_count; $i++)
        {
            $html .= $i == $page ? '<span class="current-page">' . $i . '</span>'
                : '<a href="' . $url . $i . '">' . $i . '</a>';
        }

        if ($page_count > 10 && $page < $page_count)
        {
            $html .= sprintf('<a class="next-page" href="%s">%s</a>', $url . $page_count, '尾页');
        }
        $html .= "总计 <div class=\"notice\">{$total}</div> 个结果, ";
        $html .= "共 <div class=\"notice\">{$page_count}</div> 页 ";
        $html .= '每页条数 <input id="set_page_size" value="' . $page_size . '" /> ';
        $html .= '<button id="reset_page_size">GO</button>';
        $html .= '</div> ';

        return $html;
    }

    public static function getPageSize()
    {
        $page_size = isset($_COOKIE['page_size']) ? $_COOKIE['page_size'] + 0 : 0;
        if ($page_size < HtmlModel::page_size)
        {
            $page_size = HtmlModel::page_size;
        }

        return $page_size;
    }

    public static function render($obj, $tpl, $parameters = null)
    {
        echo $obj->render($tpl, $parameters);
    }

    /**
     * @brief setFlash 设置只显示一次的信息
     *
     * @param: $flash message
     *
     * @return: void
     */
    public static function setFlash($flash)
    {
        if (! isset($_SESSION['user_info']))
        {
            $_SESSION['user_info'] = array(
                'uid' => 0,
            );
        }

        $_SESSION['user_info']['flash'] = $flash;
    }

    public static function showFlash()
    {
        if (isset($_SESSION['user_info']) && isset($_SESSION['user_info']['flash']))
        {
            echo '<div class="box box-solid box-danger box-flash">'
                . ' <div class="box-header">'
                . '  <h3 class="box-title">友情提示</h3>'
                . ' </div>'
                . '  <div class="box-body">' . $_SESSION['user_info']['flash'] . '</div>'
                . '</div>';

            self::cleanrFlash();
        }
    }

    private static function cleanrFlash()
    {
        if (isset($_SESSION['user_info']) && isset($_SESSION['user_info']['flash']))
        {
            unset($_SESSION['user_info']['flash']);
        }
    }

    /**
     * @brief escape 防 xss 攻击
     *
     * @param: $str
     *
     * @return: string
     */
    public static function escape($str)
    {
        return Util::isBinary($str) ? addslashes($str) : htmlspecialchars(trim($str), ENT_QUOTES);
    }

    /**
     * @brief autoVersion 自动创建表态文件的 version, 防止 js/css 更新后依然被缓存
     *
     * @param: $resource
     *
     * @return: string 带静态版本号的资源路径
     */
    public static function autoVersion($resource)
    {
        $version = '1.0.0';
        $file = APPLICATION_PATH . ('/' == $resource[0] ? '' : '/') . $resource;
        if (file_exists($file))
        {
            $version = md5_file($file);
        }

        echo $resource . '?v=' . $version;
    }
}
/* vi:set ts=4 sw=4 et fdm=marker: */


<?php

class SitemapAction extends CommonAction {
    public function index() {
        if(IS_POST){
            if(empty($_POST['catid'])){
                $this->error("请选择数据来源");
            }
            $map['catid']=array('in',$_POST['catid']);
            $map['status']=array('eq',1);
            
            $article=M('Article');
            $articlelist=$article->where($map)->order('create_time desc')->select();
            
            $download=M('Download');
            $downloadlist=$download->where($map)->order('create_time desc')->select();
            
            $photo=M('Photo');
            $photolist=$photo->where($map)->order('create_time desc')->select();
            
            if(isset($_POST['sitemaptype'])){
                $type=$_POST['sitemaptype'];
            }
            
            $sitemapstr="<?xml version=\"1.0\" encoding=\"UTF-8\"?>\r\n";
            switch ($type) {
                case 0:
                    $sitemapstr.=  "<urlset>\r\n";
                    break;
                case 1:
                    $sitemapstr.=  "<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">\r\n";
                    break;

            }
            foreach ($articlelist as $value) {
                $sitemapstr.=  "<url>\r\n";
                $sitemapstr.=  "<loc>Article/show?id=".$value['id']."</loc>\r\n";
                $sitemapstr.=  "<lastmod>".toDate(NOW_TIME, 'Y-m-d')."</lastmod>\r\n";
                $sitemapstr.=  "<changefreq>".$_POST['changefreq']."</changefreq>\r\n";
                $sitemapstr.=  "<priority>".$_POST['priority']."</priority>\r\n";
                $sitemapstr.=  "</url>\r\n\r\n";
            }
            foreach ($downloadlist as $value) {
                $sitemapstr.=  "<url>\r\n";
                $sitemapstr.=  "<loc>Download/show?id=".$value['id']."</loc>\r\n";
                $sitemapstr.=  "<lastmod>".toDate(NOW_TIME, 'Y-m-d')."</lastmod>\r\n";
                $sitemapstr.=  "<changefreq>".$_POST['changefreq']."</changefreq>\r\n";
                $sitemapstr.=  "<priority>".$_POST['priority']."</priority>\r\n";
                $sitemapstr.=  "</url>\r\n\r\n";
            }
            foreach ($photolist as $value) {
                $sitemapstr.=  "<url>\r\n";
                $sitemapstr.=  "<loc>Photo/show?id=".$value['id']."</loc>\r\n";
                $sitemapstr.=  "<lastmod>".toDate(NOW_TIME, 'Y-m-d')."</lastmod>\r\n";
                $sitemapstr.=  "<changefreq>".$_POST['changefreq']."</changefreq>\r\n";
                $sitemapstr.=  "<priority>".$_POST['priority']."</priority>\r\n";
                $sitemapstr.=  "</url>\r\n\r\n";
            }

            $sitemapstr.=  "</urlset>";
            
            file_put_contents("../sitemap.xml",$sitemapstr);
            $this->success("sitemap在线生成完成");
            
            
        }else{
            $cate=new CategoryModel();
            $this->list=$cate->getMyCategory();//加载栏目
            $this->display();  
        }
        
    }
    
}

?>

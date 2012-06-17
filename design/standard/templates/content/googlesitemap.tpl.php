<?php echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n" ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">

<?php foreach ($contentTree as $content):?>
	<url>
      <loc><?php $this->url($content['path_string'],'full')?></loc>
      <lastmod><?php echo date('Y-m-d',$content['created'])?></lastmod>
 </url>
<?php endforeach;?>
</urlset>

<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$robots_dir = $_SERVER['DOCUMENT_ROOT'];
$robots_file = $robots_dir.'/robots.txt';
$robots_exists = file_exists($robots_file);
$robots_writable = null;
$robots_txt = null;
if ($robots_exists)
{
	$robots_writable = is_writable($robots_file);
	$robots_txt = file_get_contents($robots_file);
}
else
{
	$robots_writable = is_writable($robots_dir);
}

$bEdit = ($_REQUEST['gdhtml']==$id) && ($_REQUEST['edit']=='true') && ($arParams["PERMISSION"]>"R");
if($_SERVER['REQUEST_METHOD']=='POST' && $_REQUEST['gdhtml']==$id)
{
	file_put_contents($robots_file, $_POST['robots_txt']);
	$arGadget["FORCE_REDIRECT"] = true;
}
?>

<?if(!$bEdit):?>

<?
	if($robots_exists)
	{
		echo nl2br(htmlspecialchars($robots_txt));
	}
	else
	{
		echo GetMessage("GD_HTML_AREA_NO_CONTENT");
	}
?>

<?if($arParams["PERMISSION"]>"R"):?>
<div class="gdhtmlareach" style="padding-top: 10px;">
<?if($robots_writable === true):?>
<a class="gdhtmlareachlink" href="<?=$GLOBALS["APPLICATION"]->GetCurPageParam("gdhtml=".$id."&edit=true", array("gdhtml", "edit"))?>"><?echo GetMessage("GD_HTML_AREA_CHANGE_LINK")?></a>
<?else:?>
<p style="color:#999"><? echo GetMessage("GD_HTML_AREA_NOT_WRITABLE") ?></p>
<?endif;?>
</div>
<?endif?>

<?else:?>

<form action="?gdhtml=<?=$id?>" method="post" id="gdf<?=$id?>">
	<input type="hidden" name="gdhtmlform" value="Y">
	<?if ($arParams["MULTIPLE"] == "Y"):?>
	<input type="hidden" name="dt_page" value="<?=$arParams["DESKTOP_PAGE"]?>">
	<?endif;?>
	<textarea name="robots_txt" style="width: 100%; height: 300px;"><? echo $robots_txt; ?></textarea>
	<?=bitrix_sessid_post()?>
</form>
<script type="text/javascript">
function gdhtmlsave()
{
	document.getElementById("gdf<?=$id?>").submit();
	return false;
}
</script>
<a href="javascript:void(0);" onclick="return gdhtmlsave();"><?echo GetMessage("GD_HTML_AREA_SAVE_LINK")?></a>
|
<a href="<?=$GLOBALS["APPLICATION"]->GetCurPageParam(($arParams["MULTIPLE"]=="Y"?"dt_page=".$arParams["DESKTOP_PAGE"]:""), array("dt_page","gdhtml","edit"))?>"><?echo GetMessage("GD_HTML_AREA_CANCEL_LINK")?></a>
<?endif?>
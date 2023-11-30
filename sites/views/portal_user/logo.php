<a href="<?= $this->data['portal_user']['link'] ?>" title = "<?= $this->data['portal_user']['label'] ?>">
    <img src="<?= $this->file_url_of('portal_logo',$this->data['portal_user']['logo']) ?>?v=<?= get_config("cash_version") ?>" alt="<?= $this->data['portal_user']['label']; ?>" />
</a>
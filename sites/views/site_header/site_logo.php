<a href="<?= outer_url() ?>" title = "<?= $this->data['site']['title'] ?>">
    <img src="<?= $this->file_url_of('logo',$this->data['site']['logo']) ?>?v=<?= get_config("cash_version") ?>" alt="<?= $this->data['site']['title']; ?>" />
</a>
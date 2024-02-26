<div class="whatsapp-chat-wrap">
    <a href='whatsapp://send?text=<?= str_replace("'","\'",$info['message']) ?>&phone=<?= $info['phone'] ?>'>
        <img src="<?= $info['image'] ?>" alt="<?= __tr("Contact with Whatsapp") ?>" />
    </a>
</div>
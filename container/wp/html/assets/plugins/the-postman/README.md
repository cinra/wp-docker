## Example

```
<form method="POST" enctype="multipart/form-data">

<?php wp_nonce_field('the-postman')?>


<input type="hidden" name="_finished" value="1" />


<input type="hidden" name="_status" value="confirm" />
<?php if (postman_is_confirmed()):?><input type="hidden" name="_finished" value="1" /><?php endif?>

<input type="hidden" name="_finish_page" value="{完了ページ}" />
<input type="hidden" name="_type" value="{メールテンプレートの指定}" />


<div class="block">
<label>E-Mail</label>
<input type="text" name="email" value="<?php the_postman('email')?>" />
<?php if(postman_is_error('email')):?><p><?php postman_the_message('email')?></p><?php endif?>
</div>

<div class="block">
<label>本文</label>
<textarea name="body"></textarea>

</div>

<input type="submit" name="submit" value="送信する" />

</form>
```
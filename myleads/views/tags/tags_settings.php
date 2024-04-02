<div id="tags_edit_wrap">
	<h3>תיוגים</h3>
	<table colspan='3' border="1" style="border-collapse:collapse">
		<tr>
			<th>#</th>
			<th>תיוג</th>
			<th>צבע</th>
			<th>שמירה</th>
			<th>מחיקה</th>
		</tr>
		<tr style="padding:20px; background: #b9daff;">
			<td>הוספת תיוג</td>
			<td>
				<form action="" method="POST">
					<input type="hidden" name="add_tag" value="1" />
					<input type="text" name="tag_data[tag_name]"  />
					
				
			</td>
			<td>
				<div class="tag-color-select">
					<input class="tag-color-input" type="hidden" name="tag_data[color_id]" value="0" />
					<?php foreach($info['tag_color_list'] as $color): ?>
						<div class="tag-color tag-color-<?= $color['id'] ?> selected-0<?= $color['id'] == '0' ?>" onclick="select_tag_color(this)" data-color_id="<?= $color['id'] ?>">-</div>		
					<?php endforeach; ?>
				</div>
			</td>
			<td style="padding:20px; background: #b9ffbb;">
					<input type="submit" value="שליחה" />
				</form>
			</td>
			<td></td>
		</tr>
		<?php foreach($this->data['tag_list'] as $tag_id=>$tag): if($tag_id!='0'): ?>


			<tr>
				<td><?= $tag_id ?></td>
				<td>
					<form action="" method="POST">
						<input type="hidden" name="edit_tag" value="<?= $tag_id ?>" />
						<input type="text" name="tag_data[tag_name]" value="<?= $tag['tag_name'] ?>" />
						
				</td>
				<td>
					<div class="tag-color-select">
						<input class="tag-color-input" type="hidden" name="tag_data[color_id]" value="<?= $tag['color_id'] ?>" />	
						<?php foreach($info['tag_color_list'] as $color): ?>
							<div class="tag-color tag-color-<?= $color['id'] ?> selected-0<?= $color['id'] == $tag['color_id'] ?>"  onclick="select_tag_color(this)" data-color_id="<?= $color['id'] ?>">-</div>	
						<?php endforeach; ?>
					</div>
				</td>
				<td style="padding:20px; background: #b9ffbb;">
						<input type="submit" value="שליחה" />
					</form>
				</td>
				<td style="padding:20px; background: #ffd7b9;">
					<form action="" method="POST">
						<input type="hidden" name="delete_tag" value="1" />
						<input type="hidden" name="tag_data[tag_id]" value="<?php echo $tag_id; ?>" />
						<input type="submit" value="מחק" onclick="return confirm('האם למחוק את הפריט?')" />
					</form>				
				</td>
			</tr>
		<?php endif; endforeach; ?>
	</table>
</div>
<script type="text/javascript">
	function select_tag_color(a_el){
		const wrap = a_el.closest(".tag-color-select");
		wrap.querySelectorAll(".tag-color").forEach(color=>{color.classList.remove("selected-01")});
		a_el.classList.add("selected-01");
		const inputc  = wrap.querySelector(".tag-color-input");
		console.log(inputc);
		wrap.querySelector(".tag-color-input").value = a_el.dataset.color_id;
		
	}
</script>
<style type="text/css">
	.tag-color{
		background: white;
		border: 3.2px solid gray;
		float: right;
		margin: 7px;
		font-size: 0px;
		width: 20px;
		height: 20px;
		cursor: pointer;
		visibility: 0.6;
	}
	.tag-color:hover, .tag-color.selected-01{
		width: 24px;
		height: 24px;
		margin: 5px;
		border-color: black;
		visibility: 1;
	}
	.tag-color.tag-color-1{
		background: #231613;
	}
	.tag-color.tag-color-2{
		background: #1f2a60;
	}
	.tag-color.tag-color-3{
		background: #0d3e81;
	}
	.tag-color.tag-color-4{
		background: #c98a10;
	}
	.tag-color.tag-color-5{
		background: #d93115;
	}
	.tag-color.tag-color-6{
		background: #f1b81a;
	}
	.tag-color.tag-color-7{
		background: #f1e728;
	}
	.tag-color.tag-color-8{
		background: #75b828;
	}
	.tag-color.tag-color-9{
		background: #2e8338;
	}
	.tag-color.tag-color-10{
		background: #5eaada;
	}
	.tag-color.tag-color-11{
		background: #efb3a1;
	}
</style>
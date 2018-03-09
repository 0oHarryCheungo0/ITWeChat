<?php use yii\helpers\Url; ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="<?=Url::to('@web/backend/public/css/mycss.css')?>"/>   
    <title>凯撒it</title>
</head>
<body>
<form class="layui-form" method="post" >
<div class="stro_list">
    <div class="stro_list_k clearfix">
        <div class="stro_list_single">
            <div class="single_title"><span><i class="layui-icon">&#xe649;</i></span>主菜单左</div>
            <div class="single_lable">
                <div class="layui-form-item">
				    <label class="layui-form-label">菜单名</label>
				    <div class="layui-input-block">
				     	<input type="text" name="menu1[name]" value="<?php echo isset($data['menu1']['name']) ? $data['menu1']['name']:""; ?>" placeholder="请输入" autocomplete="off" class="layui-input">
				    </div>
  				</div>
                <div class="single_lable_input">
                	<div class="layui-form-item">
				    	<label class="layui-form-label">name</label>
				    	<div class="layui-input-block">
				     	 <input type="text" name="menu1[sub][1][name]" value="<?php echo isset($data['menu1']['sub'][1]['name']) ? $data['menu1']['sub'][1]['name']:""; ?>" placeholder="请输入" autocomplete="off" class="layui-input">
				    	</div>
  					</div>
  			  		<div class="layui-form-item">
    					<label class="layui-form-label">type</label>
    					<div class="layui-input-block">
					      <select name="menu1[sub][1][type]"  lay-filter="aihao">
					        <option value="0" <?php if(isset($data['menu1']['sub'][1]['type']) && $data['menu1']['sub'][1]['type']=='0') echo 'selected'?>>链接</option>
					        <option value="1" <?php if(isset($data['menu1']['sub'][1]['type']) && $data['menu1']['sub'][1]['type']=='1') echo 'selected'?>>点击</option>
					        <option value="2" <?php if(isset($data['menu1']['sub'][1]['type']) && $data['menu1']['sub'][1]['type']=='2') echo 'selected'?>>禁用</option>
					      </select>
					    </div>
					</div>
					<div class="layui-form-item">
				    	<label class="layui-form-label">key/value</label>
				    	<div class="layui-input-block">
				     	 <input type="text" name="menu1[sub][1][value]" value="<?php echo isset($data['menu1']['sub'][1]['value']) ? $data['menu1']['sub'][1]['value']:""; ?>" placeholder="请输入" autocomplete="off" class="layui-input">
				    	</div>
  					</div>
              </div>
              <!--  -->
                <div class="single_lable_input">
                    <div class="layui-form-item">
				    	<label class="layui-form-label">name</label>
				    	<div class="layui-input-block">
				     	 <input type="text" name="menu1[sub][2][name]" value="<?php echo isset($data['menu1']['sub'][2]['name']) ? $data['menu1']['sub'][2]['name']:""; ?>" placeholder="请输入" autocomplete="off" class="layui-input">
				    	</div>
  					</div>
  			  		<div class="layui-form-item">
    					<label class="layui-form-label">type</label>
    					<div class="layui-input-block">
					      <select name="menu1[sub][2][type]"  lay-filter="aihao">
					        <option value="0" <?php if(isset($data['menu1']['sub'][2]['type']) && $data['menu1']['sub'][2]['type']=='0') echo 'selected'?>>链接</option>
					        <option value="1"<?php if(isset($data['menu1']['sub'][2]['type']) && $data['menu1']['sub'][2]['type']=='1') echo 'selected'?>>点击</option>
					        <option value="2" <?php if(isset($data['menu1']['sub'][2]['type']) && $data['menu1']['sub'][2]['type']=='2') echo 'selected'?>>禁用</option>
					      </select>
					    </div>
					</div>
					<div class="layui-form-item">
				    	<label class="layui-form-label">key/value</label>
				    	<div class="layui-input-block">
				     	 <input type="text" name="menu1[sub][2][value]" value="<?php echo isset($data['menu1']['sub'][2]['value']) ? $data['menu1']['sub'][2]['value']:""; ?>" placeholder="请输入" autocomplete="off" class="layui-input">
				    	</div>
  					</div>
                </div>
                <!--  -->
                <div class="single_lable_input">
                  <div class="layui-form-item">
				    	<label class="layui-form-label">name</label>
				    	<div class="layui-input-block">
				     	 <input type="text" name="menu1[sub][3][name]" value="<?php echo isset($data['menu1']['sub'][3]['name']) ? $data['menu1']['sub'][3]['name']:""; ?>" placeholder="请输入" autocomplete="off" class="layui-input">
				    	</div>
  					</div>
  			  		<div class="layui-form-item">
    					<label class="layui-form-label">type</label>
    					<div class="layui-input-block">
					      <select name="menu1[sub][3][type]"  lay-filter="aihao">
					        <option value="0" <?php if(isset($data['menu1']['sub'][3]['type']) && $data['menu1']['sub'][3]['type']=='0') echo 'selected'?>>链接</option>
					        <option value="1" <?php if(isset($data['menu1']['sub'][3]['type']) && $data['menu1']['sub'][3]['type']=='1') echo 'selected'?>>点击</option>
					        <option value="2" <?php if(isset($data['menu1']['sub'][3]['type']) && $data['menu1']['sub'][3]['type']=='2') echo 'selected'?>>禁用</option>
					      </select>
					    </div>
					</div>
					<div class="layui-form-item">
				    	<label class="layui-form-label">key/value</label>
				    	<div class="layui-input-block">
				     	 <input type="text" name="menu1[sub][3][value]" value="<?php echo isset($data['menu1']['sub'][3]['value']) ? $data['menu1']['sub'][3]['value']:""; ?>" placeholder="请输入" autocomplete="off" class="layui-input">
				    	</div>
  					</div>
                </div>
                <!--  -->
                <div class="single_lable_input">
                 <div class="layui-form-item">
				    	<label class="layui-form-label">name</label>
				    	<div class="layui-input-block">
				     	 <input type="text" name="menu1[sub][4][name]" value="<?php echo isset($data['menu1']['sub'][4]['name']) ? $data['menu1']['sub'][4]['name']:""; ?>" placeholder="请输入" autocomplete="off" class="layui-input">
				    	</div>
  					</div>
  			  		<div class="layui-form-item">
    					<label class="layui-form-label">type</label>
    					<div class="layui-input-block">
					      <select name="menu1[sub][4][type]"  lay-filter="aihao">
					        <option value="0" <?php if(isset($data['menu1']['sub'][4]['type']) && $data['menu1']['sub'][4]['type']=='0') echo 'selected'?>>链接</option>
					        <option value="1" <?php if(isset($data['menu1']['sub'][4]['type']) && $data['menu1']['sub'][4]['type']=='1') echo 'selected'?>>点击</option>
					        <option value="2" <?php if(isset($data['menu1']['sub'][4]['type']) && $data['menu1']['sub'][4]['type']=='2') echo 'selected'?>>禁用</option>
					      </select>
					    </div>
					</div>
					<div class="layui-form-item">
				    	<label class="layui-form-label">key/value</label>
				    	<div class="layui-input-block">
				     	 <input type="text" name="menu1[sub][4][value]" value="<?php echo isset($data['menu1']['sub'][4]['value']) ? $data['menu1']['sub'][4]['value']:""; ?>" placeholder="请输入" autocomplete="off" class="layui-input">
				    	</div>
  					</div>
                </div>
            </div>
        </div>
        
        <!--  -->
        <div class="stro_list_single">
            <div class="single_title"><span><i class="layui-icon">&#xe649;</i></span>主菜单左</div>
            <div class="single_lable">
                <div class="layui-form-item">
				    <label class="layui-form-label">菜单名</label>
				    <div class="layui-input-block">
				     	<input type="text" name="menu2[name]" value="<?php echo isset($data['menu2']['name']) ? $data['menu2']['name']:""; ?>" placeholder="请输入" autocomplete="off" class="layui-input">
				    </div>
  				</div>
                <div class="single_lable_input">
                	<div class="layui-form-item">
				    	<label class="layui-form-label">name</label>
				    	<div class="layui-input-block">
				     	 <input type="text" name="menu2[sub][1][name]" value="<?php echo isset($data['menu2']['sub'][1]['name']) ? $data['menu2']['sub'][1]['name']:""; ?>" placeholder="请输入" autocomplete="off" class="layui-input">
				    	</div>
  					</div>
  			  		<div class="layui-form-item">
    					<label class="layui-form-label">type</label>
    					<div class="layui-input-block">
					      <select name="menu2[sub][1][type]"  lay-filter="aihao">
					        <option value="0" <?php if(isset($data['menu2']['sub'][1]['type']) && $data['menu2']['sub'][1]['type']=='0') echo 'selected'?>>链接</option>
					        <option value="1"<?php if(isset($data['menu2']['sub'][1]['type']) && $data['menu2']['sub'][1]['type']=='1') echo 'selected'?>>点击</option>
					        <option value="2"<?php if(isset($data['menu2']['sub'][1]['type']) && $data['menu2']['sub'][1]['type']=='2') echo 'selected'?>>禁用</option>
					      </select>
					    </div>
					</div>
					<div class="layui-form-item">
				    	<label class="layui-form-label">key/value</label>
				    	<div class="layui-input-block">
				     	 <input type="text" name="menu2[sub][1][value]" value="<?php echo isset($data['menu2']['sub'][1]['value']) ? $data['menu2']['sub'][1]['value']:""; ?>" placeholder="请输入" autocomplete="off" class="layui-input">
				    	</div>
  					</div>
              </div>
              <!--  -->
                <div class="single_lable_input">
                    <div class="layui-form-item">
				    	<label class="layui-form-label">name</label>
				    	<div class="layui-input-block">
				     	 <input type="text" name="menu2[sub][2][name]" value="<?php echo isset($data['menu2']['sub'][2]['name']) ? $data['menu2']['sub'][2]['name']:""; ?>" placeholder="请输入" autocomplete="off" class="layui-input">
				    	</div>
  					</div>
  			  		<div class="layui-form-item">
    					<label class="layui-form-label">type</label>
    					<div class="layui-input-block">
					      <select name="menu2[sub][2][type]"  lay-filter="aihao">
					        <option value="0"<?php if(isset($data['menu2']['sub'][2]['type']) && $data['menu2']['sub'][2]['type']=='0') echo 'selected'?>>链接</option>
					        <option value="1"<?php if(isset($data['menu2']['sub'][2]['type']) && $data['menu2']['sub'][2]['type']=='1') echo 'selected'?>>点击</option>
					        <option value="2"<?php if(isset($data['menu2']['sub'][2]['type']) && $data['menu2']['sub'][2]['type']=='2') echo 'selected'?>>禁用</option>
					      </select>
					    </div>
					</div>
					<div class="layui-form-item">
				    	<label class="layui-form-label">key/value</label>
				    	<div class="layui-input-block">
				     	 <input type="text" name="menu2[sub][2][value]" value="<?php echo isset($data['menu2']['sub'][2]['value']) ? $data['menu2']['sub'][2]['value']:""; ?>" placeholder="请输入" autocomplete="off" class="layui-input">
				    	</div>
  					</div>
                </div>
                <!--  -->
                <div class="single_lable_input">
                  <div class="layui-form-item">
				    	<label class="layui-form-label">name</label>
				    	<div class="layui-input-block">
				     	 <input type="text" name="menu2[sub][3][name]" value="<?php echo isset($data['menu2']['sub'][3]['name']) ? $data['menu2']['sub'][3]['name']:""; ?>" placeholder="请输入" autocomplete="off" class="layui-input">
				    	</div>
  					</div>
  			  		<div class="layui-form-item">
    					<label class="layui-form-label">type</label>
    					<div class="layui-input-block">
					      <select name="menu2[sub][3][type]"  lay-filter="aihao">
					        <option value="0" <?php if(isset($data['menu2']['sub'][3]['type']) && $data['menu2']['sub'][3]['type']=='0') echo 'selected'?>>链接</option>
					        <option value="1"<?php if(isset($data['menu2']['sub'][3]['type']) && $data['menu2']['sub'][3]['type']=='1') echo 'selected'?>>点击</option>
					        <option value="2"<?php if(isset($data['menu2']['sub'][3]['type']) && $data['menu2']['sub'][3]['type']=='2') echo 'selected'?>>禁用</option>
					      </select>
					    </div>
					</div>
					<div class="layui-form-item">
				    	<label class="layui-form-label">key/value</label>
				    	<div class="layui-input-block">
				     	 <input type="text" name="menu2[sub][3][value]" value="<?php echo isset($data['menu2']['sub'][3]['value']) ? $data['menu2']['sub'][3]['value']:""; ?>" placeholder="请输入" autocomplete="off" class="layui-input">
				    	</div>
  					</div>
                </div>
                <!--  -->
                <div class="single_lable_input">
                 <div class="layui-form-item">
				    	<label class="layui-form-label">name</label>
				    	<div class="layui-input-block">
				     	 <input type="text" name="menu2[sub][4][name]" value="<?php echo isset($data['menu2']['sub'][4]['name']) ? $data['menu2']['sub'][4]['name']:""; ?>" placeholder="请输入" autocomplete="off" class="layui-input">
				    	</div>
  					</div>
  			  		<div class="layui-form-item">
    					<label class="layui-form-label">type</label>
    					<div class="layui-input-block">
					      <select name="menu2[sub][4][type]"  lay-filter="aihao">
					        <option value="0"<?php if(isset($data['menu2']['sub'][4]['type']) && $data['menu2']['sub'][4]['type']=='0') echo 'selected'?>>链接</option>
					        <option value="1"<?php if(isset($data['menu2']['sub'][4]['type']) && $data['menu2']['sub'][4]['type']=='1') echo 'selected'?>>点击</option>
					        <option value="2"<?php if(isset($data['menu2']['sub'][4]['type']) && $data['menu2']['sub'][4]['type']=='2') echo 'selected'?>>禁用</option>
					      </select>
					    </div>
					</div>
					<div class="layui-form-item">
				    	<label class="layui-form-label">key/value</label>
				    	<div class="layui-input-block">
				     	 <input type="text" name="menu2[sub][4][value]" value="<?php echo isset($data['menu2']['sub'][4]['value']) ? $data['menu2']['sub'][4]['value']:""; ?>" placeholder="请输入" autocomplete="off" class="layui-input">
				    	</div>
  					</div>
                </div>
            </div>
        </div>
        
        <!--  -->
        <div class="stro_list_single">
            <div class="single_title"><span><i class="layui-icon">&#xe649;</i></span>主菜单左</div>
            <div class="single_lable">
                <div class="layui-form-item">
				    <label class="layui-form-label">菜单名</label>
				    <div class="layui-input-block">
				     	<input type="text" name="menu3[name]" value="<?php echo isset($data['menu3']['name']) ? $data['menu3']['name']:""; ?>" placeholder="请输入" autocomplete="off" class="layui-input">
				    </div>
  				</div>
                <div class="single_lable_input">
                	<div class="layui-form-item">
				    	<label class="layui-form-label">name</label>
				    	<div class="layui-input-block">
				     	 <input type="text" name="menu3[sub][1][name]" value="<?php echo isset($data['menu3']['sub'][1]['name']) ? $data['menu3']['sub'][1]['name']:""; ?>" placeholder="请输入" autocomplete="off" class="layui-input">
				    	</div>
  					</div>
  			  		<div class="layui-form-item">
    					<label class="layui-form-label">type</label>
    					<div class="layui-input-block">
					      <select name="menu3[sub][1][type]"  lay-filter="aihao">
					        <option value="0" <?php if(isset($data['menu3']['sub'][1]['type']) && $data['menu3']['sub'][1]['type']=='0') echo 'selected'?>>链接</option>
					        <option value="1"<?php if(isset($data['menu3']['sub'][1]['type']) && $data['menu3']['sub'][1]['type']=='1') echo 'selected'?>>点击</option>
					        <option value="2"<?php if(isset($data['menu3']['sub'][1]['type']) && $data['menu3']['sub'][1]['type']=='2') echo 'selected'?>>禁用</option>
					      </select>
					    </div>
					</div>
					<div class="layui-form-item">
				    	<label class="layui-form-label">key/value</label>
				    	<div class="layui-input-block">
				     	 <input type="text" name="menu3[sub][1][value]" value="<?php echo isset($data['menu3']['sub'][1]['value']) ? $data['menu3']['sub'][1]['value']:""; ?>" placeholder="请输入" autocomplete="off" class="layui-input">
				    	</div>
  					</div>
              </div>
              <!--  -->
                <div class="single_lable_input">
                    <div class="layui-form-item">
				    	<label class="layui-form-label">name</label>
				    	<div class="layui-input-block">
				     	 <input type="text" name="menu3[sub][2][name]" value="<?php echo isset($data['menu3']['sub'][2]['name']) ? $data['menu3']['sub'][2]['name']:""; ?>" placeholder="请输入" autocomplete="off" class="layui-input">
				    	</div>
  					</div>
  			  		<div class="layui-form-item">
    					<label class="layui-form-label">type</label>
    					<div class="layui-input-block">
					      <select name="menu3[sub][2][type]"  lay-filter="aihao">
					        <option value="0"<?php if(isset($data['menu3']['sub'][2]['type']) && $data['menu3']['sub'][2]['type']=='0') echo 'selected'?>>链接</option>
					        <option value="1"<?php if(isset($data['menu3']['sub'][2]['type']) && $data['menu3']['sub'][2]['type']=='1') echo 'selected'?>>点击</option>
					        <option value="2"<?php if(isset($data['menu3']['sub'][2]['type']) && $data['menu3']['sub'][2]['type']=='2') echo 'selected'?>>禁用</option>
					      </select>
					    </div>
					</div>
					<div class="layui-form-item">
				    	<label class="layui-form-label">key/value</label>
				    	<div class="layui-input-block">
				     	 <input type="text" name="menu3[sub][2][value]" value="<?php echo isset($data['menu3']['sub'][2]['value']) ? $data['menu3']['sub'][2]['value']:""; ?>" placeholder="请输入" autocomplete="off" class="layui-input">
				    	</div>
  					</div>
                </div>
                <!--  -->
                <div class="single_lable_input">
                  <div class="layui-form-item">
				    	<label class="layui-form-label">name</label>
				    	<div class="layui-input-block">
				     	 <input type="text" name="menu3[sub][3][name]" value="<?php echo isset($data['menu3']['sub'][3]['name']) ? $data['menu3']['sub'][3]['name']:""; ?>" placeholder="请输入" autocomplete="off" class="layui-input">
				    	</div>
  					</div>
  			  		<div class="layui-form-item">
    					<label class="layui-form-label">type</label>
    					<div class="layui-input-block">
					      <select name="menu3[sub][3][type]"  lay-filter="aihao">
					        <option value="0"<?php if(isset($data['menu3']['sub'][3]['type']) && $data['menu3']['sub'][3]['type']=='0') echo 'selected'?>>链接</option>
					        <option value="1"<?php if(isset($data['menu3']['sub'][3]['type']) && $data['menu3']['sub'][3]['type']=='1') echo 'selected'?>>点击</option>
					        <option value="2"<?php if(isset($data['menu3']['sub'][3]['type']) && $data['menu3']['sub'][3]['type']=='2') echo 'selected'?>>禁用</option>
					      </select>
					    </div>
					</div>
					<div class="layui-form-item">
				    	<label class="layui-form-label">key/value</label>
				    	<div class="layui-input-block">
				     	 <input type="text" name="menu3[sub][3][value]" value="<?php echo isset($data['menu3']['sub'][3]['value']) ? $data['menu3']['sub'][3]['value']:""; ?>" placeholder="请输入" autocomplete="off" class="layui-input">
				    	</div>
  					</div>
                </div>
                <!--  -->
                <div class="single_lable_input">
                 <div class="layui-form-item">
				    	<label class="layui-form-label">name</label>
				    	<div class="layui-input-block">
				     	 <input type="text" name="menu3[sub][4][name]" value="<?php echo isset($data['menu3']['sub'][4]['name']) ? $data['menu3']['sub'][4]['name']:""; ?>" placeholder="请输入" autocomplete="off" class="layui-input">
				    	</div>
  					</div>
  			  		<div class="layui-form-item">
    					<label class="layui-form-label">type</label>
    					<div class="layui-input-block">
					      <select name="menu3[sub][4][type]"  lay-filter="aihao">
					        <option value="0"<?php if(isset($data['menu3']['sub'][4]['type']) && $data['menu3']['sub'][4]['type']=='0') echo 'selected'?>>链接</option>
					        <option value="1"<?php if(isset($data['menu3']['sub'][4]['type']) && $data['menu3']['sub'][4]['type']=='1') echo 'selected'?>>点击</option>
					        <option value="2"<?php if(isset($data['menu3']['sub'][4]['type']) && $data['menu3']['sub'][4]['type']=='2') echo 'selected'?>>禁用</option>
					      </select>
					    </div>
					</div>
					<div class="layui-form-item">
				    	<label class="layui-form-label">key/value</label>
				    	<div class="layui-input-block">
				     	 <input type="text" name="menu3[sub][4][value]" value="<?php echo isset($data['menu3']['sub'][4]['value']) ? $data['menu3']['sub'][4]['value']:""; ?>" placeholder="请输入" autocomplete="off" class="layui-input">
				    	</div>
  					</div>
                </div>
            </div>
        </div>
        
    </div>
    <div class="stro_list_bot">
      <button class="layui-btn" lay-submit lay-filter="formDemo"><i class="layui-icon">&#xe605;</i> 保存</button>
      <button type="reset" class="layui-btn layui-btn-primary"><i class="layui-icon">&#x1006;</i> 取消</button>
    </div>
   </div>
   </form>
</body>
<script>
layui.use('form', function(){
	  var form = layui.form();
	  
	  //监听提交
	  form.on('submit(formDemo)', function(data){

	  });
	});
	$('.wxset').show();
	$('#menu').attr('class','active');
</script>
</html>
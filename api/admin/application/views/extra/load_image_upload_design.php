<div class="row" style="margin-top:10px;">
                                <div class="col-lg-12">
                                    <div class="card">
                                        <div class="card-header">
                                            
                                            <h4 class="card-title mb-0" style="font-size:14px;color:black;">
                                                Upload Picture
                                                <!--<a href="#" class="btn btn-sm btn-outline-primary btn-border" style="float:right" onclick="call_set_images('');">Add Photos?</a>-->
                                                <!--<a href="#" class="btn btn-sm btn-outline-primary btn-border" style="float:right" onclick="$('#upload_image').click();">Add Photos?</a>-->
                                                <a href="#" class="btn rounded-pill btn-warning waves-effect waves-light" 
                                                   style="float:right;color:black;font-weight:bold;" onclick="call_set_images('');" >Add Pictures?</a>  
                                            </h4>
                                        </div>
                                        <!--
                                        <div class="card-header" style="display:none;" id="upload_file0">
                                            <div class="upload__box">
                                            <div class="upload__btn-box">
                                                <label class="upload__btn" id="upload_image" style="display:none;">
                                                <p><label for="outline-button" class="form-label text-muted">Upload images</label></p>
                                                <input type="file" multiple="" onchange="check_images(this);" name="images[]" id="images"  data-max_length="20" class="upload__inputfile">
                                              </label>
                                            </div>
                                            <div class="upload__img-wrap"></div>
                                            </div>
                                        </div>
                                        <div class="card-body" style="display:none;" id="upload_file">
                                            
                                        </div>
                                        -->
                                        <div class="card-body" id="upload_file">
                                            
                                        </div>    
                                        <!-- end card body -->
                                    </div>
                                    <!-- end card -->
                                </div> <!-- end col -->
                                
                                <script>
                                function call_set_images(edit='')
                                {
//                                    alert('dkk');  
//                                    alert(edit);
                                    if(edit!='')    
                                    {    
                                    if(edit=='add-new-style')    
                                    {
                                    var style_id=$("#style_id").val();    
                                    }
                                    else
                                    {    
                                    var selectize = $('#styles')[0].selectize;
                                    var selectedValue = selectize.getValue();
//                                    alert(selectize);  
//                                    alert(selectedValue);
                                    
                                    var selectedText = [];
                                    for (var i = 0; i < selectedValue.length; i++) {
                                        var option = selectize.options[selectedValue[i]];
                                        if (option) {
                                            selectedText.push(option.text);
                                        }
                                    }
                                    var style_id=selectedText;    
                                    } 
                                    
                                    }
                                    else
                                    {
                                    var style_id=$("#style_id").val();
                                    }  
//                                    alert(style_id);
                                    
                                    
//                                    if (selectedText === undefined || selectedText === null) {
//                                    var style_id=$("#style_id").val();
//                                    }
//                                    else
//                                    {
//                                    var style_id=selectedText;    
//                                    }    
//                                    alert(style_id); 
                                    
//                                    if(style_id=='')
//                                    {
//                                    var style_id=$("#styles").val();    
//                                    }  
//                                    alert(style_id);
                                    if(style_id=='')
                                    {    
                                    $("#error_toast_bar").attr("data-toast-text", "Please Select/Add Design Id First!");
                                    $("#error_toast_bar").click(); 
                                    $("#style_id").focus(); 
                                    }
                                    else
                                    {
                                    var session = "<?php echo $_SESSION['id']; ?>";
                                    var final="https://64facetscrm.com/dropzone/index.php?style_id="+style_id+"&user_id="+session;
                                    $('#upload_file').html('<iframe width="100%" height="150px" src="'+final+'"></iframe>');
                                    }    
                                }
                                </script>
                                
                                <div class="col-lg-6 is-hidden" style="display:none;">
                                    <div class="card">
                                        <div class="card-header">
                                            <h4 class="card-title mb-0">Capture Picture
                                                <a href="#" style="float:right">Add</a>
                                            </h4>
                                        </div>

                                        <div class="card-body" style="display:block;" id="capture_file">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div id="my_camera"></div> 
                                                    <br/>
                                                    <input type=button value="Take Snapshot" onClick="take_snapshot()">
                                                    <input type="hidden" name="image" class="image-tag">
                                                </div>
                                                <div class="col-md-6">
                                                    <div id="results">Your captured image will appear here...</div>
                                                </div>
                                                <div class="col-md-12 text-center">
                                                    <br/>
                                                    <button class="btn btn-success">Submit</button>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- end card body -->
                                    </div>
                                    <!-- end card -->
                                </div> <!-- end col -->
</div>
    
    
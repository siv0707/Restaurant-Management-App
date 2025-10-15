<?php include 'includes/header.php'; ?>
<script src="assets/js/ckeditor/ckeditor.js"></script>

<?php

    if (isset($_POST['submit'])) {

        $post_image = time().'_'.$_FILES['post_image']['name'];
        $file = $_FILES['post_image']['tmp_name'];
        $path = 'upload/post/'.$post_image;
        copy($file, $path);

        $data = array(
            'post_title' => $_POST['post_title'],
            'post_image' => $post_image,
            'post_description' => $_POST['post_description']
        );      

        $qry = Insert('tbl_post', $data);                                    
        redirect("post.php", "Post added successfully...");
    }

?>

<div id="content" class="pmd-content content-area dashboard">

    <div class="container-fluid full-width-container">
        <h1></h1>

        <ol class="breadcrumb text-left">
          <li><a href="dashboard.php">Dashboard</a></li>
          <li><a href="post.php">Post</a></li>
          <li class="active">Add</li>
      </ol>

      <div class="section"> 

        <form id="validationForm" method="post" enctype="multipart/form-data">
            <div class="pmd-card pmd-z-depth">
                <div class="pmd-card-body">

                    <div class="group-fields clearfix row">
                        <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                            <div class="lead">ADD POST</div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                            <p align="right"><button type="submit" class="btn pmd-ripple-effect btn-material" name="submit">Submit</button></p>
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <?php include 'includes/alert.php'; ?>
                        </div>
                    </div>

                    <div class="group-fields clearfix row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="form-group pmd-textfield">
                                <label for="post_title" class="control-label">
                                    Title *
                                </label>
                                <input type="text" name="post_title" id="post_title" placeholder="Title" class="form-control" required>
                            </div>
                        </div>
                    </div>

                    <div class="group-fields clearfix row">
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <div class="form-group pmd-textfield">
                                <label for="regular1" class="control-label">Image ( jpg / png) *</label>
                                <input type="file" name="post_image" id="post_image" class="dropify-image" data-max-file-size="3M" data-allowed-file-extensions="jpg jpeg png gif" required/>
                            </div>
                        </div>
                    </div>

                    <div class="group-fields clearfix row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="form-group pmd-textfield">
                                <label class="control-label">Description *</label>
                                <textarea required class="form-control" name="post_description"></textarea>
                                <script>                             
                                    CKEDITOR.replace('post_description');
                                    <?php if ($ENABLE_RTL_MODE == 'true') { ?>
                                        CKEDITOR.config.contentsLangDirection = 'rtl';
                                    <?php } ?>
                                </script>   
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </form>
    </div>

</div>

</div>

<?php include 'includes/footer.php'; ?>
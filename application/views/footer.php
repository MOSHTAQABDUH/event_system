    </div><!--row-->   
    
    <footer>
        <div class="col-md-12" style="text-align:center;">
            <hr> 
        </div>
    </footer>
    </div><!-- /container -->  
    
    <!-- /Load Js --> 
    <script src="<?php echo base_url().'public/js/jquery.min.js' ?>"></script> 
    <script src="<?php echo base_url().'public/js/bootstrap.min.js' ?>"></script>
    <script src="<?php echo base_url().'public/js/main.js' ?>"></script>

    <!-- Load jq code -->
    <?php 
    
    if(isset($xfooter)){
        echo $xfooter;
    }
    if(isset($xjq)){
        echo "
        <script type='text/javascript' >
            $(document).ready(function(){
                
                " . $xjq . "
            });
        </script>";
        }?>
 
    </body>
</html>

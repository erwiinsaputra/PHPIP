<style type="text/css">
    .error{
        text-align: center;
        border:1px solid black;
        background-color: #ddd;
        padding: 10px;
        width: 50%;
        margin-left: 25%;
        margin-top: 10%;
    }
</style>

<div class="error">
    <!-- <div> Error 404 </div> -->
    <h3>File Not Found</h3>
    <div>Klik Link Berikut untuk kembali ke website</div>
    <div> 
        <?php $login = @$this->session->userdata('USER')['IS_LOGIN']; 
            if($login == ''){ ?>
            <a href="<?= site_url()?>"> Home </a>
        <?php }else{ ?>
            <a href="<?= site_url()?>"> Home </a>
        <?php } ?>
    </div>
</div>
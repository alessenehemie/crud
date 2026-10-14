<?php
if (isset($_SESSION['message'])) :
?>
 <div style="background-color: #d1e7dd; color: #0f5132; padding: 15px; margin-bottom: 20px; border-radius: 6px; border: 1px solid #badbcc;">
        <?= $_SESSION['message']; ?>
    </div>
<?php
    unset($_SESSION['message']);
endif;
?>
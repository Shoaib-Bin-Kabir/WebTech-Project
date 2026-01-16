<?php
?>

<?php if (isset($errors['general'])): ?>
    <div class="error-banner">
        <?php echo htmlspecialchars($errors['general']); ?>
    </div>
<?php endif; ?>

<div class="form-container sidebar-form">
    <form action="../Controller/updateProfile.php" method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="name">Name<span class="required">*</span></label>
            <input type="text" id="name" name="name" value="<?php echo isset($previousValues['name']) ? htmlspecialchars($previousValues['name']) : htmlspecialchars($customer['Name'] ?? ''); ?>" required>
            <?php if (isset($errors['name'])): ?>
                <span class="error-message"><?php echo $errors['name']; ?></span>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label for="phone">Phone Number<span class="required">*</span></label>
            <input type="text" id="phone" name="phone" value="<?php echo isset($previousValues['phone']) ? htmlspecialchars($previousValues['phone']) : htmlspecialchars($customer['Phone_Number'] ?? ''); ?>" required>
            <?php if (isset($errors['phone'])): ?>
                <span class="error-message"><?php echo $errors['phone']; ?></span>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label for="nid">NID<span class="required">*</span></label>
            <input type="text" id="nid" name="nid" value="<?php echo isset($previousValues['nid']) ? htmlspecialchars($previousValues['nid']) : htmlspecialchars($customer['NID'] ?? ''); ?>" required>
            <?php if (isset($errors['nid'])): ?>
                <span class="error-message"><?php echo $errors['nid']; ?></span>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label for="photo">Profile Photo</label>
            <?php if (!empty($customer['Photo'])): ?>
                <div class="current-photo">
                    <img src="<?php echo htmlspecialchars($customer['Photo']); ?>" alt="Current Photo">
                    <small>Current Photo</small>
                </div>

                <div class="checkbox-row">
                    <input type="checkbox" id="remove_photo" name="remove_photo" value="1">
                    <label for="remove_photo">Remove current photo</label>
                </div>
            <?php endif; ?>
            <input type="file" id="photo" name="photo" accept="image/*">
            <?php if (isset($errors['photo'])): ?>
                <span class="error-message"><?php echo $errors['photo']; ?></span>
            <?php endif; ?>
        </div>

        <div class="form-buttons">
            <button type="submit" class="btn-primary">Update</button>
            <a href="dashboard.php" class="btn-secondary">Cancel</a>
        </div>
    </form>
</div>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 text-primary"><i class="fas fa-user-edit me-2"></i>My Profile</h5>
            </div>
            <div class="card-body">
                <form action="<?= $base_url ?>/profile/update" method="POST" enctype="multipart/form-data">

                    <div class="text-center mb-4">
                        <div class="position-relative d-inline-block">
                            <?php if (!empty($user['profile_pic'])): ?>
                                <img src="<?= $base_url . '/' . $user['profile_pic'] ?>" alt="Profile Picture"
                                    class="rounded-circle img-thumbnail"
                                    style="width: 150px; height: 150px; object-fit: cover;">
                            <?php else: ?>
                                <div class="bg-light rounded-circle d-flex align-items-center justify-content-center mx-auto"
                                    style="width: 150px; height: 150px; font-size: 3rem; color: #ccc;">
                                    <i class="fas fa-user"></i>
                                </div>
                            <?php endif; ?>
                            <label for="profile_pic"
                                class="position-absolute bottom-0 end-0 bg-primary text-white rounded-circle p-2 cursor-pointer"
                                style="cursor: pointer;">
                                <i class="fas fa-camera"></i>
                            </label>
                            <input type="file" id="profile_pic" name="profile_pic" class="d-none" accept="image/*"
                                onchange="previewImage(this)">
                        </div>
                        <div class="small text-muted mt-2">Click icon to change</div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">Full Name</label>
                            <input type="text" class="form-control" id="name" name="name"
                                value="<?= htmlspecialchars($user['name']) ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email" class="form-control" id="email" name="email"
                                value="<?= htmlspecialchars($user['email']) ?>" required>
                        </div>
                    </div>

                    <hr class="my-4">
                    <h6 class="text-muted mb-3">Change Password (Optional)</h6>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="password" class="form-label">New Password</label>
                            <input type="password" class="form-control" id="password" name="password">
                            <div class="form-text">Leave blank if you don't want to change it.</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="confirm_password" class="form-label">Confirm New Password</label>
                            <input type="password" class="form-control" id="confirm_password" name="confirm_password">
                        </div>
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="fas fa-save me-2"></i>Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function previewImage(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                // Find the image element. If it doesn't exist (because of the else block), we might need to be smarter.
                // But with the current structure, let's just target the img-thumbnail if it exists, or the div wrapper.
                var img = document.querySelector('.rounded-circle.img-thumbnail');
                if (img) {
                    img.src = e.target.result;
                } else {
                    // If there was no image before, replace the placeholder div with an image
                    var placeholder = document.querySelector('.bg-light.rounded-circle');
                    if (placeholder) {
                        var newImg = document.createElement('img');
                        newImg.src = e.target.result;
                        newImg.className = 'rounded-circle img-thumbnail';
                        newImg.style.width = '150px';
                        newImg.style.height = '150px';
                        newImg.style.objectFit = 'cover';
                        placeholder.parentNode.replaceChild(newImg, placeholder);
                    }
                }
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
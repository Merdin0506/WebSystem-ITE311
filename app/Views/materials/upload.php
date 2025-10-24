<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Upload Course Material</h5>
                </div>
                <div class="card-body">
                    <?php if (session()->getFlashdata('error')): ?>
                        <div class="alert alert-danger" role="alert">
                            <?= esc(session()->getFlashdata('error')) ?>
                        </div>
                    <?php endif; ?>
                    <?php if (session()->getFlashdata('success')): ?>
                        <div class="alert alert-success" role="alert">
                            <?= esc(session()->getFlashdata('success')) ?>
                        </div>
                    <?php endif; ?>

                    <form action="<?= site_url('materials/upload/' . $course_id) ?>" method="post" enctype="multipart/form-data" class="needs-validation" novalidate>
                        <?= csrf_field() ?>

                        <div class="mb-3">
                            <label for="file" class="form-label">Select file</label>
                            <input type="file" class="form-control" id="file" name="file" required>
                            <div class="form-text">Allowed types: pdf, doc, docx, ppt, pptx, zip. Max size: 5MB.</div>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">Upload</button>
                            <a href="<?= site_url('admin/courses') ?>" class="btn btn-outline-secondary">Back to Courses</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->extend('student/layout') ?>

<?= $this->section('content') ?>
<div class="container-fluid px-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-download me-2"></i>
                        My Course Materials
                    </h5>
                </div>
                
                <div class="card-body">
                    <!-- Success/Error Messages -->
                    <?php if (session()->getFlashdata('success')): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            <?= session()->getFlashdata('success') ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <?php if (session()->getFlashdata('error')): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            <?= session()->getFlashdata('error') ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <?php if (empty($materials)): ?>
                        <div class="text-center py-5">
                            <i class="fas fa-folder-open fa-4x text-muted mb-3"></i>
                            <h5 class="text-muted">No Materials Available</h5>
                            <p class="text-muted">Your instructors haven't uploaded any materials yet, or you're not enrolled in any courses.</p>
                            <a href="/student/available-courses" class="btn btn-primary">
                                <i class="fas fa-search me-2"></i>Browse Courses
                            </a>
                        </div>
                    <?php else: ?>
                        <!-- Materials by Course -->
                        <?php
                        $courseGroups = [];
                        foreach ($materials as $material) {
                            $courseKey = $material['course_id'];
                            if (!isset($courseGroups[$courseKey])) {
                                $courseGroups[$courseKey] = [
                                    'course_name' => $material['course_name'],
                                    'course_code' => $material['course_code'],
                                    'materials' => []
                                ];
                            }
                            $courseGroups[$courseKey]['materials'][] = $material;
                        }
                        ?>

                        <?php foreach ($courseGroups as $courseId => $courseData): ?>
                            <div class="card mb-4">
                                <div class="card-header bg-primary text-white">
                                    <h6 class="mb-0">
                                        <i class="fas fa-book me-2"></i>
                                        <?= esc($courseData['course_name']) ?> (<?= esc($courseData['course_code']) ?>)
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <?php foreach ($courseData['materials'] as $material): ?>
                                            <div class="col-md-6 col-lg-4 mb-3">
                                                <div class="card h-100 border-light shadow-sm">
                                                    <div class="card-body d-flex flex-column">
                                                        <div class="text-center mb-3">
                                                            <?php
                                                            $ext = pathinfo($material['file_name'], PATHINFO_EXTENSION);
                                                            $icon = 'fa-file';
                                                            $iconColor = 'text-secondary';
                                                            $bgColor = 'bg-light';
                                                            
                                                            switch (strtolower($ext)) {
                                                                case 'pdf':
                                                                    $icon = 'fa-file-pdf';
                                                                    $iconColor = 'text-white';
                                                                    $bgColor = 'bg-danger';
                                                                    break;
                                                                case 'doc':
                                                                case 'docx':
                                                                    $icon = 'fa-file-word';
                                                                    $iconColor = 'text-white';
                                                                    $bgColor = 'bg-primary';
                                                                    break;
                                                                case 'ppt':
                                                                case 'pptx':
                                                                    $icon = 'fa-file-powerpoint';
                                                                    $iconColor = 'text-white';
                                                                    $bgColor = 'bg-warning';
                                                                    break;
                                                                case 'jpg':
                                                                case 'jpeg':
                                                                case 'png':
                                                                    $icon = 'fa-file-image';
                                                                    $iconColor = 'text-white';
                                                                    $bgColor = 'bg-success';
                                                                    break;
                                                                case 'txt':
                                                                    $icon = 'fa-file-alt';
                                                                    $iconColor = 'text-white';
                                                                    $bgColor = 'bg-info';
                                                                    break;
                                                            }
                                                            ?>
                                                            <div class="<?= $bgColor ?> rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                                                <i class="fas <?= $icon ?> <?= $iconColor ?> fa-2x"></i>
                                                            </div>
                                                        </div>
                                                        
                                                        <h6 class="card-title text-center mb-2" title="<?= esc($material['file_name']) ?>">
                                                            <?= strlen($material['file_name']) > 25 ? substr($material['file_name'], 0, 25) . '...' : esc($material['file_name']) ?>
                                                        </h6>
                                                        
                                                        <div class="text-center text-muted small mb-3">
                                                            <div><i class="fas fa-calendar me-1"></i><?= date('M d, Y', strtotime($material['created_at'])) ?></div>
                                                            <div><i class="fas fa-tag me-1"></i><?= strtoupper($ext) ?> File</div>
                                                        </div>
                                                        
                                                        <div class="mt-auto text-center">
                                                            <a href="/materials/download/<?= $material['id'] ?>" 
                                                               class="btn btn-primary btn-sm download-btn"
                                                               data-material-id="<?= $material['id'] ?>">
                                                                <i class="fas fa-download me-2"></i>Download
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>

                        <!-- Statistics Card -->
                        <div class="card bg-light">
                            <div class="card-body">
                                <div class="row text-center">
                                    <div class="col-md-3">
                                        <h4 class="text-primary"><?= count($courseGroups) ?></h4>
                                        <small class="text-muted">Courses with Materials</small>
                                    </div>
                                    <div class="col-md-3">
                                        <h4 class="text-success"><?= count($materials) ?></h4>
                                        <small class="text-muted">Total Materials</small>
                                    </div>
                                    <div class="col-md-3">
                                        <h4 class="text-info">
                                            <?php
                                            $fileTypes = [];
                                            foreach ($materials as $material) {
                                                $ext = strtolower(pathinfo($material['file_name'], PATHINFO_EXTENSION));
                                                $fileTypes[$ext] = true;
                                            }
                                            echo count($fileTypes);
                                            ?>
                                        </h4>
                                        <small class="text-muted">File Types</small>
                                    </div>
                                    <div class="col-md-3">
                                        <h4 class="text-warning">
                                            <?= !empty($materials) ? date('M d', strtotime($materials[0]['created_at'])) : 'N/A' ?>
                                        </h4>
                                        <small class="text-muted">Latest Upload</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add download tracking
    document.querySelectorAll('.download-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            const materialId = this.getAttribute('data-material-id');
            const originalContent = this.innerHTML;
            
            // Show downloading state
            this.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Downloading...';
            this.disabled = true;
            
            // Reset after 3 seconds
            setTimeout(() => {
                this.innerHTML = originalContent;
                this.disabled = false;
            }, 3000);
        });
    });
});
</script>
<?= $this->endSection() ?>
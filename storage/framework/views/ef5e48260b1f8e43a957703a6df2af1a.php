

<?php $__env->startSection('content'); ?>
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0"><i class="bi bi-envelope"></i> Enviar Convite</h4>
            </div>
            <div class="card-body">
                <form method="POST" action="<?php echo e(route('admin.invites.store')); ?>" id="inviteForm">
                    <?php echo csrf_field(); ?>
                    
                    <div class="mb-3">
                        <label for="name" class="form-label">Nome do Usuário *</label>
                        <input type="text" class="form-control" id="name" name="name" value="<?php echo e(old('name')); ?>" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="email" class="form-label">Email *</label>
                        <input type="email" class="form-control" id="email" name="emails" value="<?php echo e(old('emails')); ?>" required>
                        <small class="text-muted">Digite um email válido.</small>
                    </div>
                    
                    <div class="mb-3">
                        <label for="company_id" class="form-label">Empresa *</label>
                        <select class="form-select" id="company_id" name="company_id" required>
                            <option value="">Selecione...</option>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $companies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $company): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($company->id); ?>" <?php echo e(old('company_id') == $company->id ? 'selected' : ''); ?>>
                                    <?php echo e($company->name); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="sector_id" class="form-label">Setor *</label>
                        <select class="form-select" id="sector_id" name="sector_id" required>
                            <option value="">Primeiro selecione uma empresa</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="role" class="form-label">Perfil *</label>
                        <select class="form-select" id="role" name="role" required>
                            <option value="user" <?php echo e(old('role') == 'user' ? 'selected' : ''); ?>>Usuário Comum</option>
                            <option value="technician" <?php echo e(old('role') == 'technician' ? 'selected' : ''); ?>>Técnico</option>
                            <option value="admin" <?php echo e(old('role') == 'admin' ? 'selected' : ''); ?>>Administrador</option>
                        </select>
                    </div>
                    
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="<?php echo e(route('admin.invites.index')); ?>" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Voltar
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-send"></i> Enviar Convite
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var companySelect = document.getElementById('company_id');
        var sectorSelect = document.getElementById('sector_id');
        var baseUrl = '<?php echo e(url("/")); ?>';
        
        function loadSectors(companyId) {
            if (!companyId) {
                sectorSelect.innerHTML = '<option value="">Primeiro selecione uma empresa</option>';
                return;
            }
            
            sectorSelect.innerHTML = '<option value="">Carregando...</option>';
            
            fetch(baseUrl + '/admin/sectors-by-company/' + companyId)
                .then(function(response) {
                    if (!response.ok) {
                        throw new Error('HTTP error ' + response.status);
                    }
                    return response.json();
                })
                .then(function(data) {
                    sectorSelect.innerHTML = '<option value="">Selecione um setor</option>';
                    if (data.length === 0) {
                        sectorSelect.innerHTML = '<option value="">Nenhum setor cadastrado</option>';
                    }
                    for (var i = 0; i < data.length; i++) {
                        sectorSelect.innerHTML += '<option value="' + data[i].id + '">' + data[i].name + '</option>';
                    }
                })
                .catch(function(error) {
                    console.error('Erro:', error);
                    sectorSelect.innerHTML = '<option value="">Erro ao carregar setores</option>';
                });
        }
        
        if (companySelect) {
            companySelect.addEventListener('change', function() {
                loadSectors(this.value);
            });
            
            if (companySelect.value) {
                loadSectors(companySelect.value);
            }
        }
    });
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\helpdesk\resources\views/admin/invites/create.blade.php ENDPATH**/ ?>
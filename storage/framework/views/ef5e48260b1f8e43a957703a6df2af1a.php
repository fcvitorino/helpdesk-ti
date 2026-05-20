

<?php $__env->startSection('content'); ?>
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-success text-white">
                <h4 class="mb-0">Novo Convite</h4>
            </div>
            <div class="card-body">
                
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($errors->any()): ?>
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li><?php echo e($error); ?></li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </ul>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                
                <form method="POST" action="<?php echo e(route('admin.invites.store')); ?>" id="inviteForm">
                    <?php echo csrf_field(); ?>
                    
                    <div class="mb-3">
                        <label class="form-label">E-mail <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control" 
                               value="<?php echo e(old('email')); ?>" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Perfil <span class="text-danger">*</span></label>
                        <select name="role" class="form-select" required>
                            <option value="user">Usuário</option>
                            <option value="technician">Técnico</option>
                            <option value="admin">Administrador</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Empresa <span class="text-danger">*</span></label>
                        <select name="company_id" id="company_id" class="form-select" required>
                            <option value="">Selecione...</option>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $companies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $company): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($company->id); ?>" <?php echo e(old('company_id') == $company->id ? 'selected' : ''); ?>>
                                    <?php echo e($company->name); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Setor <span class="text-danger">*</span></label>
                        <select name="sector_id" id="sector_id" class="form-select" required>
                            <option value="">Primeiro selecione uma empresa</option>
                        </select>
                    </div>
                    
                    <button type="submit" class="btn btn-success w-100">
                        <i class="bi bi-envelope"></i> Enviar Convite
                    </button>
                    
                    <a href="<?php echo e(route('admin.invites.index')); ?>" class="btn btn-secondary w-100 mt-2">
                        <i class="bi bi-arrow-left"></i> Voltar
                    </a>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // Função para carregar setores via AJAX
    document.getElementById('company_id').addEventListener('change', function() {
        var companyId = this.value;
        var sectorSelect = document.getElementById('sector_id');
        
        if (companyId) {
            // Limpar e desabilitar o select enquanto carrega
            sectorSelect.innerHTML = '<option value="">Carregando...</option>';
            sectorSelect.disabled = true;
            
            // Buscar setores via AJAX
            fetch('<?php echo e(url("/admin/sectors/by-company")); ?>/' + companyId)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Erro na requisição');
                    }
                    return response.json();
                })
                .then(data => {
                    sectorSelect.innerHTML = '<option value="">Selecione um setor</option>';
                    if (data.length === 0) {
                        sectorSelect.innerHTML = '<option value="">Nenhum setor cadastrado</option>';
                    }
                    data.forEach(sector => {
                        var option = document.createElement('option');
                        option.value = sector.id;
                        option.textContent = sector.name;
                        sectorSelect.appendChild(option);
                    });
                    sectorSelect.disabled = false;
                })
                .catch(error => {
                    console.error('Erro ao carregar setores:', error);
                    sectorSelect.innerHTML = '<option value="">Erro ao carregar setores</option>';
                    sectorSelect.disabled = false;
                });
        } else {
            sectorSelect.innerHTML = '<option value="">Primeiro selecione uma empresa</option>';
            sectorSelect.disabled = false;
        }
    });
    
    // Se já tiver uma empresa selecionada (ex: após erro de validação)
    document.addEventListener('DOMContentLoaded', function() {
        var companyId = document.getElementById('company_id').value;
        if (companyId) {
            var event = new Event('change');
            document.getElementById('company_id').dispatchEvent(event);
        }
    });
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\helpdesk\resources\views/admin/invites/create.blade.php ENDPATH**/ ?>
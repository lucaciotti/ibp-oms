sail artisan make:controller PlannedTaskController

//Se view con DynamicContent
sail artisan livewire:make PlannedTask/Content
sail artisan livewire:make PlannedTask/PlannedTaskModalEdit
sail artisan make:datatable PlannedTask/PlannedTaskTable PlannedTask


sail artisan livewire:make PlanImportFile/PlanImportFileModal

sail artisan livewire:make PlanImportFile/PlanImportFileModalEdit

<!-- JOBS -->
sail artisan make:job ProcessTempTasks

<!-- EXCEL -->
php artisan make:import UsersImport --model=User
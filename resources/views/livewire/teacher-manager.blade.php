<div class="p-6 max-w-7xl mx-auto space-y-8">
    <div>
        <flux:heading size="xl" class="text-3xl font-bold tracking-tight">Teacher Management</flux:heading>
        <flux:subheading>Manage faculty profiles, system access, and professional designations.</flux:subheading>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Create Form -->
        <div class="bg-white dark:bg-zinc-900 p-6 rounded-2xl shadow-sm border border-zinc-200 dark:border-zinc-800 space-y-6">
            <div class="flex items-center gap-3 text-indigo-600 dark:text-indigo-400">
                <flux:icon icon="user-plus" size="md" />
                <h3 class="font-bold text-lg text-zinc-900 dark:text-white">New Teacher Account</h3>
            </div>

            <div class="space-y-4">
                <flux:input wire:model="name" label="Full Display Name" placeholder="e.g. Dr. Gabriel Tamo" />
                <flux:input wire:model="email" type="email" label="University Email" placeholder="gabrielTamo@university.edu" />
                <flux:input wire:model="password" type="password" label="Temporary Password" viewable />
                
                <div class="grid grid-cols-2 gap-2">
                    <flux:input wire:model="firstName" label="First Name" />
                    <flux:input wire:model="lastName" label="Last Name" />
                </div>

                <div class="grid grid-cols-2 gap-2">
                    <flux:input wire:model="staffCode" label="Staff ID" placeholder="EMP-001" />
                    <flux:select wire:model="teacherType" label="Type">
                        <option value="">Select...</option>
                        <option value="INTERNAL">Internal</option>
                        <option value="EXTERNAL">External</option>
                    </flux:select>
                </div>
                
                <flux:button wire:click="createTeacher" variant="primary" class="w-full">Create Faculty Profile</flux:button>
            </div>
        </div>

        <!-- Teacher List -->
        <div class="lg:col-span-2 bg-white dark:bg-zinc-900 rounded-2xl shadow-sm border border-zinc-200 dark:border-zinc-800 overflow-hidden">
            <div class="p-6 border-b border-zinc-100 dark:border-zinc-800 flex items-center justify-between">
                <h3 class="font-bold text-lg text-zinc-900 dark:text-white">Faculty Directory</h3>
                <flux:badge size="sm" color="zinc" variant="subtle">{{ $teachers->count() }} Profiles</flux:badge>
            </div>

            <flux:table>
                <flux:table.columns>
                    <flux:table.column>Staff ID</flux:table.column>
                    <flux:table.column>Faculty Member</flux:table.column>
                    <flux:table.column>Email</flux:table.column>
                    <flux:table.column>Status</flux:table.column>
                </flux:table.columns>
                
                <flux:table.rows>
                    @foreach($teachers as $teacher)
                        <flux:table.row>
                            <flux:table.cell class="font-mono text-xs uppercase">{{ $teacher->staff_code }}</flux:table.cell>
                            <flux:table.cell class="font-medium text-zinc-900 dark:text-white">
                                {{ $teacher->first_name }} {{ $teacher->last_name }}
                            </flux:table.cell>
                            <flux:table.cell>{{ $teacher->user->email }}</flux:table.cell>
                            <flux:table.cell>
                                <flux:badge size="sm" :color="$teacher->teacher_type === 'INTERNAL' ? 'blue' : 'orange'" variant="subtle">
                                    {{ $teacher->teacher_type }}
                                </flux:badge>
                            </flux:table.cell>
                        </flux:table.row>
                    @endforeach
                </flux:table.rows>
            </flux:table>
        </div>
    </div>
</div>

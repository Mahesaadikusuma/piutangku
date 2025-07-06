<div>
    <flux:heading level="1" size="xl">Customer</flux:heading>
    <flux:subheading size="xl" class="">Manange Your Customer Create</flux:subheading>
    <flux:breadcrumbs class="my-3">
        <flux:breadcrumbs.item :href="route('dashboard')">Dashboard</flux:breadcrumbs.item>
        <flux:breadcrumbs.item :href="route('master-data.customer.index')">Customers</flux:breadcrumbs.item>
        <flux:breadcrumbs.item>Customer Create</flux:breadcrumbs.item>
    </flux:breadcrumbs>
    <flux:separator variant="subtle" class="mb-6" />
    <div class="p-4 bg-white dark:bg-neutral-900 shadow-xl rounded-xl sm:p-7">
        <div class="">
            <flux:label>Customers</flux:label>
            <div class="flex items-center gap-4 ">
                <flux:button.group>
                    <flux:button :variant="$userType == 'newUser' ? 'primary' : 'ghost'" wire:click="setUserType('newUser')" class="cursor-pointer">New User</flux:button>
                    <flux:button class="cursor-pointer" :variant="$userType == 'notNewUser' ? 'primary' : 'ghost'" wire:click="setUserType('notNewUser')">Not New User</flux:button>
                </flux:button.group>
            </div>
        </div>
        <flux:separator class="my-5" />
        <div class="">
            <div class="">
                @if ($userType == 'newUser')
                    <livewire:company.customers.customer-new-user />
                @endif
            </div>

            <div class="">
                @if ($userType == 'notNewUser')
                    <livewire:company.customers.customer-not-new-user />
                @endif
            </div>
        </div>
    </div>
</div>

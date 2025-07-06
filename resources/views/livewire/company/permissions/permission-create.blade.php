<div>
    <flux:modal name="create-permission" class="md:w-96">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Create Permission</flux:heading>
                <flux:text class="mt-2">add a new Permission.</flux:text>
            </div>

            <form wire:submit="store" class="flex flex-col gap-6">
                <flux:input wire:model.lazy="form.name" label="Name" placeholder="Your name" type="text"  />
                <div class="flex">
                    <flux:spacer />

                    <flux:button type="submit" variant="primary">Save changes</flux:button>
                </div>
            </form>
        </div>
    </flux:modal>
</div>

<div>
    <flux:modal name="edit-permission" class="md:w-96">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Edit Permission</flux:heading>
                <flux:text class="mt-2">Edit a Permission.</flux:text>
            </div>

            <form wire:submit="update" class="flex flex-col gap-6">
                <flux:input wire:model.lazy="form.name" label="Name" placeholder="Your name" type="text"  />
                <div class="flex">
                    <flux:spacer />

                    <flux:button type="submit" variant="primary">Save changes</flux:button>
                </div>
            </form>
        </div>
    </flux:modal>
</div>

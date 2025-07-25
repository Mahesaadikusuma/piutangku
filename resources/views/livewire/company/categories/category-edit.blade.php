<div>
    <flux:modal name="edit-category" class="min-w-[25rem] md:w-[30rem]">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Edit Category</flux:heading>
                <flux:text class="mt-2">Edit a Category.</flux:text>
            </div>

            <form wire:submit="update" class="flex flex-col gap-6">
                <flux:input wire:model.lazy="form.name" label="Name" placeholder="Your name" type="text"  />
                <flux:input type="file" wire:model="form.thumbnail" label="Thumbnail"/>
                <div class="flex">
                    <flux:spacer />

                    <flux:button type="submit" variant="primary">Save changes</flux:button>
                </div>
            </form>
        </div>
    </flux:modal>
</div>

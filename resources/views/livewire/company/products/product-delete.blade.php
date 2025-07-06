<div>
    <flux:modal name="delete-product" class="min-w-[25rem]">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Delete Product?</flux:heading>

                <flux:text class="mt-2">
                    <p>Are you sure you want to delete this Product?</p>
                    <p>This action cannot be reversed.</p>
                </flux:text>
            </div>

            <div class="flex gap-2">
                <flux:spacer />

                <flux:modal.close>
                    <flux:button variant="ghost" class="cursor-pointer">Cancel</flux:button>
                </flux:modal.close>

                <flux:button type="submit" variant="danger" class="cursor-pointer" wire:click='delete'>Delete project</flux:button>
            </div>
        </div>
    </flux:modal>
</div>

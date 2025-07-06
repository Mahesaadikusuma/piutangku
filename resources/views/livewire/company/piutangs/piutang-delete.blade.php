<div>
    <flux:modal name="delete-piutang" class="min-w-[25rem]">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Delete Piutang?</flux:heading>

                <flux:text class="mt-2">
                    <p>Are you sure you want to delete this Piutang?</p>
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

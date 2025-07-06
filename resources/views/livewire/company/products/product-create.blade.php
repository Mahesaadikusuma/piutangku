<div>
    <flux:modal name="create-product" class="min-w-[25rem] md:w-[50rem]">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Create Product</flux:heading>
                <flux:text class="mt-2">Add A New Product.</flux:text>
            </div>

            <form wire:submit="store" class="flex flex-col gap-6">
                <flux:input wire:model.lazy="form.code" label="Kode Product" placeholder="Kode Product" type="text"  />
                <flux:input wire:model.lazy="form.name" label="Name" placeholder="Product Name" type="text"  />
                <flux:select  placeholder="Pilih Category..." label="Category" wire:model.lazy="form.category_id">
                    <flux:select.option>Pilih Categories</flux:select.option>
                    @foreach ($this->categories as $category)
                        <flux:select.option value="{{$category->id}}">{{$category->name}}</flux:select.option>
                    @endforeach
                </flux:select>
                <flux:input wire:model.lazy="form.price" label="Price" placeholder="Product Price" type="number"  />
                <flux:input wire:model.lazy="form.stock" label="Stock" placeholder="Stock Product" type="number"  />
                <flux:input type="file" wire:model="form.thumbnail" label="Thumbnail"/>
                <flux:textarea wire:model.lazy='form.description'
                    label="Description" rows="2"
                    placeholder="Description Product"
                />
                <div class="flex">
                    <flux:spacer />

                    <flux:button type="submit" variant="primary">Save changes</flux:button>
                </div>
            </form>
        </div>
    </flux:modal>
</div>

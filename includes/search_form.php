    <form action="" method="GET">
        <div class="row py-2">
            <div class="col-md-4">
                <input type="text" class="form-control" placeholder="Search something..." name="q" value="<?php echo old('q'); ?>" />
            </div>
            <div class="col-md-4">
                <select name="category" class="form-control">
                    <option value="">Filter by Category</option>
                    <option value="sports">Sports</option>
                    <option value="health">Health</option>
                </select>
            </div>
            <div class="col-md-4">
                <button class="btn btn-primary">Submit</button>
            </div>
        </div>
    </form>
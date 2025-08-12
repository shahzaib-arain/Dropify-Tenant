<div class="row">
    <div class="col-md-6">
        <label for="base_cost" class="form-label">Base Cost</label>
        <input type="number" step="0.01" name="base_cost" id="base_cost" class="form-control"
               value="{{ old('base_cost', $rate->base_cost ?? '') }}" required>
    </div>
    <div class="col-md-6">
        <label for="rate_per_kg" class="form-label">Rate per Kg</label>
        <input type="number" step="0.01" name="rate_per_kg" id="rate_per_kg" class="form-control"
               value="{{ old('rate_per_kg', $rate->rate_per_kg ?? '') }}">
    </div>
    <div class="col-md-6 mt-3">
        <label for="rate_per_cm3" class="form-label">Rate per cm³</label>
        <input type="number" step="0.01" name="rate_per_cm3" id="rate_per_cm3" class="form-control"
               value="{{ old('rate_per_cm3', $rate->rate_per_cm3 ?? '') }}">
    </div>
    <div class="col-md-6 mt-3">
        <label for="rate_per_km" class="form-label">Rate per Km</label>
        <input type="number" step="0.01" name="rate_per_km" id="rate_per_km" class="form-control"
               value="{{ old('rate_per_km', $rate->rate_per_km ?? '') }}">
    </div>
    <div class="col-md-6 mt-3">
        <label for="flat_rate" class="form-label">Flat Rate</label>
        <input type="number" step="0.01" name="flat_rate" id="flat_rate" class="form-control"
               value="{{ old('flat_rate', $rate->flat_rate ?? '') }}">
    </div>
</div>

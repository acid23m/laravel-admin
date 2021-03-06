{{--sortable grid--}}
<div class="sortable-{{ $component_id }}">
    {{ $slot }}
</div>

{{--save button--}}
<form class="js-save-order-{{ $component_id }}" action="{{ $action }}" method="post">
    @csrf
    @method('put')
    <input type="hidden" name="{{ $inputName }}" value="">
    <button class="btn btn-primary mt-3">{{ __('Save') }}</button>
</form>


<style>
    .sortable-{{ $component_id }}   {
        display: grid;
        grid-template-columns: repeat({{ $gridColumns }}, 1fr);
        grid-template-rows: auto;
        grid-gap: 8px;
    }

    .sortable-{{ $component_id }} .sortable-item > * {
        height: 100%;
    }

    .sortable-{{ $component_id }} .sortable-item:not(.disabled) {
        cursor: grab;
    }

    @media (max-width: 991px) {
        .sortable-{{ $component_id }}   {
            grid-template-columns: repeat({{ floor($gridColumns / 2) }}, 1fr);
        }
    }
</style>


<script>
  window.deferredCallbacks.sortModels{{ $component_id }} = function (w, d) {
    let sortedData = [];
    let saveSortForm = d.querySelector('.js-save-order-{{ $component_id }}');
    let sortedIdsInput = saveSortForm.querySelector('input[type=hidden][name="{{ $inputName }}"]');

    w.sortable('.sortable-{{ $component_id }}', {
      items: ':not(.disabled)',
      forcePlaceholderSize: true,
      orientation: 'horizontal'
    });

    w.sortable('.sortable-{{ $component_id }}')[0].addEventListener('sortupdate', function (e) {
      sortedData = [];

      e.detail.origin.items.forEach(item => {
        sortedData.push(item.dataset.id);
      });

      sortedIdsInput.value = JSON.stringify(sortedData);
    });

    saveSortForm.addEventListener('submit', function (e) {
      if (sortedIdsInput.value === '') {
        e.preventDefault();
      }
    });
  };
</script>

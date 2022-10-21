var handleOutsideClick = {};

export default {
  bind(el, binding) {
    handleOutsideClick[el.id] = (e) => {
      e.stopPropagation();

      // Extract the handler and exclude from the binding value
      const { handler, exclude } = binding.value;

      // Set varaiable to keep track of if the
      // clicked element is in the exclude list
      let clickedOnExcludedEl = false;
      // If the target element has no classes, it won't
      // be in the exclude list: skip the check
      if (e.target._prevClass !== undefined) {
        // For each exclude name check if it matches
        // any of the target elements classes
        for (const className of exclude) {
          clickedOnExcludedEl = e.target._prevClass.includes(className);

          if (clickedOnExcludedEl) {
            // Once we have found one match stop looking
            break;
          }
        }
      }

      // Don't call the handler if our directive
      // element contains the target element
      if (!(el.contains(e.target) || clickedOnExcludedEl)) {
        handler();
      }
    };

    document.addEventListener('click', handleOutsideClick[el.id]);
    document.addEventListener('touchstart', handleOutsideClick[el.id]);
  },

  unbind(el) {
    document.removeEventListener('click', handleOutsideClick[el.id]);
    document.removeEventListener('touchstart', handleOutsideClick[el.id]);
  },
};

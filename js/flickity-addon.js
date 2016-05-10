Flickity.createMethods.push('_createVisibleCells');

Flickity.prototype._createVisibleCells = function() {
  this.on('cellSelect', this.setVisibleCells);
};

// Add .is-visible to elements fitting inside viewport
Flickity.prototype.setVisibleCells = function() {
  var min = this.selectedElement.offsetLeft;
  var max = this.viewport.offsetWidth + min;

  this.cells.forEach(function(item) {
    if (item.element.offsetLeft < min) {
      classie.remove(item.element, 'is-visible');
    }
    else if ((item.element.offsetLeft + item.size.width) > max) {
      classie.remove(item.element, 'is-visible');
    }
    else {
      classie.add(item.element, 'is-visible');
    }
  });
};

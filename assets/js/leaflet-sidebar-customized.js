/**
 * @name Sidebar
 * @class L.Control.Sidebar
 * @extends L.Control
 * @param {string} id - The id of the sidebar element (without the # character)
 * @param {Object} [options] - Optional options object
 * @param {string} [options.position=left] - Position of the sidebar: 'left' or 'right'
 * @param {string} [options.container] - ID of a predefined sidebar container that should be used
 * @see L.control.sidebar
 */
L.Control.Sidebar = L.Control.extend(/** @lends L.Control.Sidebar.prototype */ {
    includes: L.Evented ? L.Evented.prototype : L.Mixin.Events,

    options: {
        container: null,
        position: 'left'
    },

    /**
     * Create a new sidebar on this object.
     *
     * @constructor
     * @param {Object} [options] - Optional options object
     * @param {string} [options.position=left] - Position of the sidebar: 'left' or 'right'
     * @param {string} [options.container] - ID of a predefined sidebar container that should be used
     */
    initialize: function(options) {

        this._tabitems = [];
        this._panes = [];

        L.setOptions(this, options);
        return this;
    },

    /**
     * Add this sidebar to the specified map.
     *
     * @returns {Sidebar}
     */
    onAdd: function() {
        let i, child, tabContainers, container;

        // use container from previous onAdd()
        container = this._container

        // use the container given via options.
        if (!container) {
            container = this._container || typeof this.options.container === 'string'
            ? L.DomUtil.get(this.options.container)
            : this.options.container;
        }

        // Find paneContainer in DOM & store reference
        this._paneContainer = container.querySelector('div.leaflet-sidebar-content');

        // Find tabContainerTop & tabContainerBottom in DOM & store reference
        tabContainers = container.querySelectorAll('div.leaflet-sidebar-tabs');
        this._tabContainerTop    = tabContainers[0] || null;

        // Store Tabs in Collection for easier iteration
        for (i = 0; i < this._tabContainerTop.children.length; ++i) {
            child = this._tabContainerTop.children[i];
            child._sidebar = this;
            child._id = child.getAttribute('href'); // FIXME: this could break for links!
            this._tabitems.push(child);
        }

        // Store Panes in Collection for easier iteration
        for (i = 0; i < this._paneContainer.children.length; ++i) {
            child = this._paneContainer.children[i];
            if (child.tagName === 'DIV' &&
                L.DomUtil.hasClass(child, 'leaflet-sidebar-pane')) {
                this._panes.push(child);
            }
        }

        // set click listeners for tab & close buttons
        for (i = 0; i < this._tabitems.length; ++i) {
            this._tabClick(this._tabitems[i], 'on');
        }

        // leaflet moves the returned container to the right place in the DOM
        return container;
    },

    /**
     * @method addTo(map: Map): this
     * Adds the control to the given map. Overrides the implementation of L.Control,
     * changing the DOM mount target from map._controlContainer.topleft to map._container
     */
    addTo: function (map) {
        this._map = map;

        this._container = this.onAdd();

        L.DomUtil.addClass(this._container, 'leaflet-control');
        L.DomUtil.addClass(this._container, 'leaflet-sidebar-' + this.getPosition());
        if (L.Browser.touch)
            L.DomUtil.addClass(this._container, 'leaflet-touch');

        // when adding to the map container, we should stop event propagation
        L.DomEvent.disableScrollPropagation(this._container);
        L.DomEvent.disableClickPropagation(this._container);
        L.DomEvent.on(this._container, 'contextmenu', L.DomEvent.stopPropagation);

        return this;
    },

   /**
     * Open sidebar (if it's closed) and show the specified tab.
     *
     * @param {string} id - The ID of the tab to show (without the # character)
     * @returns {L.Control.Sidebar}
     */
    open: function(id) {
        let i, child, tab;

        tab = id.slice(1);

        // Hide old active contents and show new content
        for (i = 0; i < this._panes.length; ++i) {
            child = this._panes[i];
            if (child.id === tab)
                L.DomUtil.addClass(child, 'active');
            else
                L.DomUtil.removeClass(child, 'active');
        }

        // Remove old active highlights and set new highlight
        for (i = 0; i < this._tabitems.length; i++) {
            child = this._tabitems[i];
            if (child.getAttribute('href') === id)
                L.DomUtil.addClass(child, 'active');
            else
                L.DomUtil.removeClass(child, 'active');
        }

        this.fire('content', { id: id });

        // Open sidebar if it's closed
        if (L.DomUtil.hasClass(this._container, 'collapsed')) {
            this.fire('opening');
            L.DomUtil.removeClass(this._container, 'collapsed');
        }

        return this;
    },

    /**
     * Close the sidebar (if it's open).
     *
     * @returns {L.Control.Sidebar}
     */
    close: function() {
        let i;

        // Remove old active highlights
        for (i = 0; i < this._tabitems.length; ++i) {
            let child = this._tabitems[i];
            L.DomUtil.removeClass(child, 'active');
        }
        for (i = 0; i < this._panes.length; ++i) {
            let child = this._panes[i];
            L.DomUtil.removeClass(child, 'active');
        }
        // close sidebar, if it's opened
        if (!L.DomUtil.hasClass(this._container, 'collapsed')) {
            this.fire('closing');
            L.DomUtil.addClass(this._container, 'collapsed');
        }

        return this;
    },

    onTabClick: function(e) {
        // `this` points to the tab DOM element!
        if (L.DomUtil.hasClass(this, 'active')) {
            this._sidebar.close();
        } else if (!L.DomUtil.hasClass(this, 'disabled')) {
            if (typeof this._button === 'string') // an url
                window.location.href = this._button;
            else if (typeof this._button === 'function') // a clickhandler
                this._button(e);
            else // a normal pane
                this._sidebar.open(this.getAttribute('href'));
        }
    },

    /**
     * (un)registers the onclick event for the given tab,
     * depending on the second argument.
     * @private
     *
     * @param {DOMelement} [tab]
     * @param {String} [on] 'on' or 'off'
     */
    _tabClick: function(tab, on) {
        if (!tab.hasAttribute('href') || tab.getAttribute('href')[0] !== '#')
            return;

        if (on === 'on') {
            L.DomEvent
                .on(tab, 'click', L.DomEvent.preventDefault, tab)
                .on(tab, 'click', this.onTabClick, tab);
        } else {
            L.DomEvent.off(tab, 'click', this.onTabClick, tab);
        }
    },
});

/**
 * Create a new sidebar.
 *
 * @example
 * var sidebar = L.control.sidebar({ container: 'sidebar' }).addTo(map);
 *
 * @param {Object} [options] - Optional options object
 * @param {string} [options.position=left] - Position of the sidebar: 'left' or 'right'
 * @param {string} [options.container] - ID of a predefined sidebar container that should be used
 * @returns {Sidebar} A new sidebar instance
 */
L.control.sidebar = function(options) {
    return new L.Control.Sidebar(options);
};

(function (window, document) {
    'use strict';

    class EngDropdown {
        constructor(root, options) {
            this.root = root;
            this.trigger = root.querySelector('.eng-dropdown__trigger');
            this.label = root.querySelector('[data-dropdown-label]');
            this.panel = root.querySelector('[data-dropdown-panel]');
            this.menu = root.querySelector('.eng-dropdown__menu');
            this.options = [];
            this.activeIndex = -1;
            this.onSelect = options.onSelect;
            this.onOpen = options.onOpen || function () {};
            this.bind();
        }

        bind() {
            this.trigger.addEventListener('click', () => this.toggle());
            this.trigger.addEventListener('keydown', event => this.onTriggerKeydown(event));
            this.menu.addEventListener('keydown', event => this.onMenuKeydown(event));
            document.addEventListener('pointerdown', event => {
                if (!this.root.contains(event.target)) this.close();
            });
            window.addEventListener('resize', () => this.isOpen() && this.fitViewport(), { passive: true });
        }

        isOpen() { return this.root.classList.contains('is-open'); }

        open(preferredIndex) {
            if (this.trigger.disabled || !this.options.length) return;
            document.querySelectorAll('[data-eng-dropdown].is-open').forEach(node => {
                if (node !== this.root && node.engDropdown) node.engDropdown.close();
            });
            this.root.classList.add('is-open');
            this.trigger.setAttribute('aria-expanded', 'true');
            this.fitViewport();
            this.onOpen();
            if (Number.isInteger(preferredIndex)) requestAnimationFrame(() => this.focusOption(preferredIndex));
        }

        close(options) {
            if (!this.isOpen()) return;
            this.root.classList.remove('is-open');
            this.trigger.setAttribute('aria-expanded', 'false');
            this.activeIndex = -1;
            this.options.forEach(option => option.classList.remove('is-active'));
            if (options && options.restoreFocus) this.trigger.focus();
        }

        toggle() { this.isOpen() ? this.close() : this.open(); }

        fitViewport() {
            const rect = this.trigger.getBoundingClientRect();
            this.panel.style.maxHeight = Math.max(96, Math.min(320, window.innerHeight - rect.bottom - 12)) + 'px';
        }

        setOptions(items) {
            this.menu.replaceChildren();
            this.options = items.map((item, index) => {
                const option = document.createElement('li');
                option.className = 'eng-dropdown__option';
                option.id = this.menu.id + '-option-' + index;
                option.role = 'option';
                option.tabIndex = -1;
                option.dataset.value = item.id;
                option.textContent = item.name;
                option.addEventListener('pointermove', () => this.setActive(index, false));
                option.addEventListener('click', () => {
                    this.close();
                    this.onSelect(item.id, item.name, item);
                });
                this.menu.appendChild(option);
                return option;
            });
            this.close();
        }

        clear(label) { this.setOptions([]); if (label) this.setLabel(label); }
        setLabel(label) { this.label.textContent = label; }
        setDisabled(disabled) {
            this.trigger.disabled = disabled;
            this.trigger.setAttribute('aria-disabled', disabled ? 'true' : 'false');
            this.root.classList.toggle('is-disabled', disabled);
            if (disabled) this.close();
        }

        setActive(index, focus) {
            if (!this.options.length) return;
            this.activeIndex = (index + this.options.length) % this.options.length;
            this.options.forEach((option, optionIndex) => option.classList.toggle('is-active', optionIndex === this.activeIndex));
            this.trigger.setAttribute('aria-activedescendant', this.options[this.activeIndex].id);
            if (focus) this.options[this.activeIndex].focus({ preventScroll: true });
            this.options[this.activeIndex].scrollIntoView({ block: 'nearest' });
        }

        focusOption(index) { this.setActive(index, true); }

        onTriggerKeydown(event) {
            if (event.key === 'ArrowDown' || event.key === 'ArrowUp') {
                event.preventDefault();
                this.open(event.key === 'ArrowUp' ? this.options.length - 1 : 0);
            } else if (event.key === 'Escape') {
                this.close();
            }
        }

        onMenuKeydown(event) {
            if (event.key === 'ArrowDown' || event.key === 'ArrowUp') {
                event.preventDefault();
                this.focusOption(this.activeIndex + (event.key === 'ArrowDown' ? 1 : -1));
            } else if (event.key === 'Home') {
                event.preventDefault(); this.focusOption(0);
            } else if (event.key === 'End') {
                event.preventDefault(); this.focusOption(this.options.length - 1);
            } else if (event.key === 'Enter' || event.key === ' ') {
                event.preventDefault(); this.options[this.activeIndex]?.click();
            } else if (event.key === 'Escape' || event.key === 'Tab') {
                this.close({ restoreFocus: event.key === 'Escape' });
            }
        }
    }

    window.createEngDropdown = function (root, options) {
        const dropdown = new EngDropdown(root, options || {});
        root.engDropdown = dropdown;
        return dropdown;
    };
})(window, document);
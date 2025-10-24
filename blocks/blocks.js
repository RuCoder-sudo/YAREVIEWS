(function(wp) {
    const { registerBlockType } = wp.blocks;
    const { InspectorControls, ServerSideRender } = wp.blockEditor || wp.editor;
    const { PanelBody, RangeControl, SelectControl, TextControl, ToggleControl } = wp.components;
    const { __ } = wp.i18n;
    const { createElement: el } = wp.element;

    registerBlockType('yareviews/slider', {
        title: __('YAREVIEWS Slider', 'yareviews'),
        description: __('Отображение отзывов в виде слайдера', 'yareviews'),
        icon: 'star-filled',
        category: 'widgets',
        attributes: {
            count: {
                type: 'number',
                default: 5
            },
            minRating: {
                type: 'number',
                default: 4
            },
            theme: {
                type: 'string',
                default: 'light'
            },
            slidesPerView: {
                type: 'number',
                default: 3
            },
            autoplay: {
                type: 'boolean',
                default: true
            }
        },
        edit: function(props) {
            const { attributes, setAttributes } = props;
            
            return [
                el(InspectorControls, {},
                    el(PanelBody, { title: __('Настройки слайдера', 'yareviews') },
                        el(RangeControl, {
                            label: __('Количество отзывов', 'yareviews'),
                            value: attributes.count,
                            onChange: (value) => setAttributes({ count: value }),
                            min: 1,
                            max: 20
                        }),
                        el(RangeControl, {
                            label: __('Минимальный рейтинг', 'yareviews'),
                            value: attributes.minRating,
                            onChange: (value) => setAttributes({ minRating: value }),
                            min: 1,
                            max: 5
                        }),
                        el(RangeControl, {
                            label: __('Слайдов на экране', 'yareviews'),
                            value: attributes.slidesPerView,
                            onChange: (value) => setAttributes({ slidesPerView: value }),
                            min: 1,
                            max: 4
                        }),
                        el(SelectControl, {
                            label: __('Тема', 'yareviews'),
                            value: attributes.theme,
                            onChange: (value) => setAttributes({ theme: value }),
                            options: [
                                { label: __('Светлая', 'yareviews'), value: 'light' },
                                { label: __('Тёмная', 'yareviews'), value: 'dark' }
                            ]
                        }),
                        el(ToggleControl, {
                            label: __('Автопрокрутка', 'yareviews'),
                            checked: attributes.autoplay,
                            onChange: (value) => setAttributes({ autoplay: value })
                        })
                    )
                ),
                el(ServerSideRender, {
                    block: 'yareviews/slider',
                    attributes: attributes
                })
            ];
        },
        save: function() {
            return null;
        }
    });

    registerBlockType('yareviews/badge', {
        title: __('YAREVIEWS Badge', 'yareviews'),
        description: __('Плавающий виджет с отзывами', 'yareviews'),
        icon: 'star-filled',
        category: 'widgets',
        attributes: {
            position: {
                type: 'string',
                default: 'bottom-right'
            },
            text: {
                type: 'string',
                default: 'Наши отзывы'
            },
            theme: {
                type: 'string',
                default: 'light'
            }
        },
        edit: function(props) {
            const { attributes, setAttributes } = props;
            
            return [
                el(InspectorControls, {},
                    el(PanelBody, { title: __('Настройки бейджа', 'yareviews') },
                        el(SelectControl, {
                            label: __('Позиция', 'yareviews'),
                            value: attributes.position,
                            onChange: (value) => setAttributes({ position: value }),
                            options: [
                                { label: __('Снизу слева', 'yareviews'), value: 'bottom-left' },
                                { label: __('Снизу справа', 'yareviews'), value: 'bottom-right' }
                            ]
                        }),
                        el(TextControl, {
                            label: __('Текст', 'yareviews'),
                            value: attributes.text,
                            onChange: (value) => setAttributes({ text: value })
                        }),
                        el(SelectControl, {
                            label: __('Тема', 'yareviews'),
                            value: attributes.theme,
                            onChange: (value) => setAttributes({ theme: value }),
                            options: [
                                { label: __('Светлая', 'yareviews'), value: 'light' },
                                { label: __('Тёмная', 'yareviews'), value: 'dark' }
                            ]
                        })
                    )
                ),
                el(ServerSideRender, {
                    block: 'yareviews/badge',
                    attributes: attributes
                })
            ];
        },
        save: function() {
            return null;
        }
    });

    registerBlockType('yareviews/grid', {
        title: __('YAREVIEWS Grid', 'yareviews'),
        description: __('Сетка отзывов', 'yareviews'),
        icon: 'star-filled',
        category: 'widgets',
        attributes: {
            count: {
                type: 'number',
                default: 6
            },
            minRating: {
                type: 'number',
                default: 4
            },
            columns: {
                type: 'number',
                default: 3
            },
            theme: {
                type: 'string',
                default: 'light'
            }
        },
        edit: function(props) {
            const { attributes, setAttributes } = props;
            
            return [
                el(InspectorControls, {},
                    el(PanelBody, { title: __('Настройки сетки', 'yareviews') },
                        el(RangeControl, {
                            label: __('Количество отзывов', 'yareviews'),
                            value: attributes.count,
                            onChange: (value) => setAttributes({ count: value }),
                            min: 1,
                            max: 20
                        }),
                        el(RangeControl, {
                            label: __('Минимальный рейтинг', 'yareviews'),
                            value: attributes.minRating,
                            onChange: (value) => setAttributes({ minRating: value }),
                            min: 1,
                            max: 5
                        }),
                        el(RangeControl, {
                            label: __('Количество колонок', 'yareviews'),
                            value: attributes.columns,
                            onChange: (value) => setAttributes({ columns: value }),
                            min: 2,
                            max: 4
                        }),
                        el(SelectControl, {
                            label: __('Тема', 'yareviews'),
                            value: attributes.theme,
                            onChange: (value) => setAttributes({ theme: value }),
                            options: [
                                { label: __('Светлая', 'yareviews'), value: 'light' },
                                { label: __('Тёмная', 'yareviews'), value: 'dark' }
                            ]
                        })
                    )
                ),
                el(ServerSideRender, {
                    block: 'yareviews/grid',
                    attributes: attributes
                })
            ];
        },
        save: function() {
            return null;
        }
    });
})(window.wp);

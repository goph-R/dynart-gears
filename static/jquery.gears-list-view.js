$.fn.listView = function(url, options) {

    // set up template
    const template = `
        <nav class="pagination" role="navigation" aria-label="pagination">            
            
        </nav>
        <table class="table is-fullwidth is-striped is-hoverable is-fullwidth">
            <thead></thead>
            <tbody></tbody>
        </table>
    `;

    $(this).addClass('gears-list-view');
    if (!$(this).html()) {
        $(this).html(template);
    }

    // get constants
    const pager = $(this).find('nav');
    const thead = $(this).find('thead');
    const tbody = $(this).find('tbody');
    const that = this;
    
    // set up options
    options.text = options.text || '';
    options.page = options.page || 0;
    options.pageSize = options.pageSize || 7;
    options.orderBy = options.orderBy || 'name';
    options.orderDir = options.orderDir || 'asc';

    this.refresh = function() {
        $.get(url, options, function(content) {
            const data = JSON.parse(content);
            that.createHeaders(thead, data, options);
            that.createContent(tbody, data, options);
            that.createPager(pager, data, options);
        });
    };

    this.createHeaders = function(thead, data, options) {
        thead.html('');
        const row = $('<tr>');
        for (let i = 0; i < data.headers.length; i++) {
            const header = data.headers[i];
            const headerElem = that.createHeader(header, options);
            row.append(headerElem);
        }
        thead.append(row);
    };    

    this.createHeader = function(header, options) {
        const result = $('<th>');
        let label = this.createHeaderLabel(header, options);
        let link = this.createHeaderLink(label, header, options);
        result.append(link);
        if (header.field == options.orderBy) {            
            result.addClass('gears-list-view-header-active');
        } else {
            result.removeClass('gears-list-view-header-active');
        }
        result.css('width', header.width);
        return result;
    };

    this.getUpArrow = function() {
        return '&#8593';
    }

    this.getDownArrow = function() {
        return '&#8595';
    }

    this.createHeaderLabel = function(header, options) {
        let label = header.label;
        if (header.field == options.orderBy) {
            label += options.orderDir == 'asc' ? this.getUpArrow() : this.getDownArrow();
        }
        return label;
    };

    this.createHeaderLink = function(label, header, options) {
        const link = $('<a>');
        link.html(label);
        link.click(function () {
            if (options.orderBy == header.field) {
                options.orderDir = options.orderDir == 'asc' ? 'desc' : 'asc';
            } else {
                options.orderDir = 'asc';
            }
            options.orderBy = header.field;
            that.refresh();
        });
        return link;        
    }

    this.createContent = function(tbody, data, options) {
        tbody.html('');
        for (let j = 0; j < data.items.length; j++) {
            const row = $('<tr>');
            for (let i = 0; i < data.headers.length; i++) {
                const header = data.headers[i];
                const cell = $('<td>');
                cell.text(data.items[j][header.field]);
                cell.css('width', header.width);
                row.append(cell);                
            }
            tbody.append(row);
        }
    };

    this.createPager = function(pager, data, options) {

        const allPages = Math.ceil(data.count / options.pageSize);

        // prev, next buttons
        const prevLink = $('<a>');
        const nextLink = $('<a>');
        pager.html('');
        prevLink.addClass('pagination-previous');
        prevLink.text('<');
        nextLink.addClass('pagination-next');
        nextLink.text('>');
        if (!options.page && allPages > 1) {
            prevLink.attr('disabled', 'disabled');
        }
        if (options.page == allPages - 1) {
            nextLink.attr('disabled', 'disabled');
        }
        pager.append(prevLink);
        pager.append(nextLink);

        nextLink.click(function() {
            if (options.page == allPages - 1) {
                return;
            }
            options.page++;
            that.refresh();
        });

        prevLink.click(function() {
            if (!options.page) {
                return;
            }
            options.page--;
            that.refresh();
        });

        // pages
        const pageList = $('<ul>');
        pageList.addClass(['pagination-list', 'gears-list-view-pager']);
        for (let i = 0; i < allPages; i++) {
            const link = this.createPagerLink(i, options);
            link.click(function() {
                options.page = i;
                that.refresh();
            });
            pageList.append(link);
        }
        pager.append(pageList);
    }

    this.createPagerLink = function(page, options) {
        const listItem = $('<li>');
        const link = $('<a>');
        link.addClass(['pagination-link', page == options.page ? 'is-current' : '']);
        link.text(page + 1);
        listItem.append(link);
        return listItem;
    }

    this.refresh();

    return this;
};
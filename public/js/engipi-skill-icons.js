(function (window) {
    'use strict';
    const database = window.EngipiSkillIconDatabase || {};
    const base = database.base_path || '/assets/skill-icons/';

    function normalize(value) {
        return String(value || '').normalize('NFKD').toLocaleLowerCase('en-US')
            .replace(/[®™©]/g, '').replace(/\+/g, ' plus ').replace(/&/g, ' and ')
            .replace(/[^\p{L}\p{N}]+/gu, ' ').trim().replace(/\s+/g, ' ');
    }

    function matches(name, terms) {
        return terms.some(term => {
            const normalized = normalize(term);
            return name === normalized || name.startsWith(normalized + ' ') || name.includes(' ' + normalized + ' ');
        });
    }

    function result(item, matched) {
        return {
            path: base + item.icon_path,
            tier: item.level === 1 ? 'product' : item.level === 2 ? 'company' : item.level === 3 ? 'category' : 'generic',
            level: item.level,
            category: item.fallback_category || item.name || 'engineering',
            matched: matched || null,
        };
    }

    function resolveInfo(skill) {
        if (skill && skill.skill_type === 'field') return result(database.field, null);
        const name = normalize(skill && skill.name);
        const entries = (database.entries || []).slice().sort((a, b) => a.level - b.level);
        const entry = entries.find(item => matches(name, item.names || []));
        if (entry) return result(entry, (entry.names || [])[0]);
        const category = (database.categories || []).find(item => matches(name, item.keywords || []));
        return result(category || database.generic, null);
    }

    function resolve(skill) { return resolveInfo(skill).path; }
    function isFallback(path) { return /\/(fallback-|architecture\.svg)/.test(String(path || '')); }
    window.EngipiSkillIcons = Object.freeze({ normalize, resolve, resolveInfo, isFallback });
})(window);
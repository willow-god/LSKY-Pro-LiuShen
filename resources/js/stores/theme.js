const THEME_KEY = 'theme';

const getStoredTheme = () => {
    try {
        return localStorage.getItem(THEME_KEY);
    } catch (error) {
        return null;
    }
};

const prefersDark = () => window.matchMedia('(prefers-color-scheme: dark)').matches;

const applyTheme = (theme) => {
    document.documentElement.classList.toggle('dark', theme === 'dark');
    document.documentElement.style.colorScheme = theme;
};

export default {
    isDark: false,

    init() {
        const theme = this.resolveTheme();
        this.isDark = theme === 'dark';
        applyTheme(theme);
    },

    resolveTheme() {
        const stored = getStoredTheme();

        if (stored === 'dark' || stored === 'light') {
            return stored;
        }

        return prefersDark() ? 'dark' : 'light';
    },

    setTheme(theme) {
        this.isDark = theme === 'dark';
        applyTheme(theme);

        try {
            localStorage.setItem(THEME_KEY, theme);
        } catch (error) {
            // noop
        }
    },

    toggle() {
        this.setTheme(this.isDark ? 'light' : 'dark');
    }
};

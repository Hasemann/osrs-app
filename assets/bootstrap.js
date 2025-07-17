import { startStimulusApp } from '@symfony/stimulus-bridge';

// Automatically loads all controllers from ./controllers
export const app = startStimulusApp(require.context(
    '@symfony/stimulus-bridge/lazy-controller-loader!./controllers',
    true,
    /\.[jt]sx?$/
));

// Register any custom controllers here if needed
// app.register('some_controller', SomeImportedController);

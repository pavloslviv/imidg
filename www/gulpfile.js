var gulp         = require('gulp'),
    sass         = require('gulp-sass'),
    cssnano      = require('gulp-cssnano'),
    csscomb      = require('gulp-csscomb'),
    autoprefixer = require('gulp-autoprefixer'),
    livereload   = require('gulp-livereload'),
    concat       = require('gulp-concat'),
    rename       = require('gulp-rename'),
    browserSync  = require('browser-sync'),
    gutil        = require('gulp-util'),
    uglify       = require('gulp-uglify');

gulp.task('sass', function(){
    return gulp.src('template/assets/scss/**/*.scss')

        .pipe(sass().on('error', function(e){
            gutil.log(e);
        }))
        .pipe(autoprefixer({
            browsers: ['last 15 versions'],
            cascade: false
        }))
        .pipe(gulp.dest('template/assets/css'))

});

gulp.task('scripts', function(){
    return gulp.src([
        'template/assets/vendor/jquery/jquery.min.js',
        'template/assets/vendor/slick-carousel/slick.min.js',
        'template/assets/vendor/star-rating/rating.js',
        'template/assets/vendor/form-styler/jquery.formstyler.js',
        'template/assets/vendor/magnific-popup/jquery.magnific-popup.js',
        'template/assets/vendor/fancybox/jquery.fancybox.js',
        'template/assets/vendor/fancybox/jquery.mousewheel.min.js',
        'template/assets/vendor/mega-dropdown/modernizr.js',
        'template/assets/vendor/mega-dropdown/jquery.menu-aim.js',
        'template/assets/vendor/mega-dropdown/main.js',
        'template/assets/vendor/custom-scroll/jquery.mCustomScrollbar.js',
        'template/assets/vendor/jquery-ui/jquery-ui.min.js',
        'template/assets/vendor/jquery-ui/jquery.ui.touch-punch.min.js',
        'template/assets/vendor/jquery-mask/jquery.mask.min.js',
        'template/assets/vendor/masonry/masonry.pkgd.js',
        'template/assets/vendor/underscore.js',
        'template/assets/vendor/nouislider/nouislider.js',
        'template/assets/vendor/nouislider/wNumb.js'
    ])

        .pipe(concat('libs.min.js'))
        .pipe(uglify())
        .pipe(gulp.dest('template/assets/js'))
});

gulp.task('css-libs', function(){
    return gulp.src([
        'template/assets/vendor/bootstrap/bootstrap.css',
        'template/assets/vendor/font-awesome/font-awesome.css',
        'template/assets/vendor/slick-carousel/slick.css',
        // 'template/assets/vendor/star-rating/rating.css',
        'template/assets/vendor/form-styler/jquery.formstyler.css',
        'template/assets/vendor/magnific-popup/magnific-popup.css',
        'template/assets/vendor/fancybox/jquery.fancybox.css',
        'template/assets/vendor/mega-dropdown/style.css',
        'template/assets/vendor/custom-scroll/jquery.mCustomScrollbar.css',
        'template/assets/vendor/nouislider/nouislider.css'
    ])

        .pipe(concat('libs.min.css'))
        .pipe(cssnano({zindex: false}))
        .pipe(gulp.dest('template/assets/css'))
});

gulp.task('browser-sync', function(){
    browserSync({
        proxy: "http://imidg.loc/",
        notify: false
    });
});


gulp.task('watch', ['css-libs', 'scripts'], function(){

    gulp.watch('template/assets/scss/**/*.scss', [sass]);
    gulp.watch('template/*.html');
    gulp.watch('template/assets/css/*.css');
    gulp.watch('template/assets/js/*.js');
});

gulp.task('default', ['watch']);


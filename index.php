<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tienda de ropa</title>
    <link rel="stylesheet" href="build/css/app.css">
</head>
<body>
    
    <header class="header inicio">

        <div class="contenedor contenido-header">

            <div class="barra">
                <a href="index.html" class="logo">
                   <!--Imagen del logo-->
                   <img src="build/img/iconos/logo.webp" alt="Logo de la tienda">
                </a>

                <h1>Tienda de ropa Online para hombres</h1>

                <nav class="navegacion">
                    <div class="iconos-header">
                        <div class="icono">
                            <a href="carrito.html">
                                <img src="build/img/iconos/carrito-compras.svg" alt="Carrito de compras" loading="lazy">
                            </a>
                        </div>
                        
                        <div class="icono">
                            <a href="inventario.html">
                                <img src="build/img/iconos/estrella.svg" alt="Inventario" loading="lazy">
                            </a>
                        </div>
                        
                        <div class="icono">
                            <a href="login.html">
                                <img src="build/img/iconos/user.svg" alt="Login" loading="lazy">
                            </a>
                        </div>

                    </div> <!-- fin iconos navegacion -->
                </nav> 

            </div> <!-- fin barra -->

        </div> <!-- fin contenedor del header -->

        <p class="slogan">Tu estilo, a un clic.</p>

    </header> <!-- fin del header -->



    <!--Barra de busquedas después del header -->
    <section class="contenido-productos">

        <a href="productos.html" class="boton boton-amarillo">
            Productos a la venta
        </a>        
        
    </section> <!-- fin de la sección con enlace a la página de productos -->



    <main class="contenedor main">
        
        <section class="main-playeras">
            <a href="productos.html">
                <img src="build/img/playeras/playeraIndex.webp" alt="Playeras" loading="lazy">
            </a>

            <div class="descripcion">

                <a href="productos.html"> <h1>Playeras</h1> </a>
                

                <p>Descubre nuestra colección de playeras, diseñadas para ser el pilar de tu guardarropa. 
                    Cada pieza está confeccionada con materiales de la más alta calidad, desde el algodón premium 
                    más suave y transpirable hasta innovadoras mezclas de tejidos que garantizan una durabilidad 
                    excepcional y un ajuste perfecto lavado tras lavado. Ya sea que busques un corte clásico y minimalista 
                    para un look limpio, o un diseño gráfico audaz que exprese tu personalidad, nuestra selección ofrece la 
                    base ideal para cualquier atuendo, combinando comodidad y estilo sin esfuerzo.</p>

                <p>Lo que realmente distingue a nuestras playeras es la obsesión por el detalle y el ajuste impecable. 
                    Hemos perfeccionado nuestros cortes para que te sientas tan bien como te ves, ofreciendo una silueta 
                    moderna que favorece sin sacrificar el confort. Cuando eliges una de nuestras playeras, 
                    eliges calidad que se siente y se nota.</p>
            </div>
             

        </section>

         <section class="main-chamarras">

            <div class="descripcion">

                <a href="productos.html"> <h1>Chamarras</h1> </a>
                

                <p>Nuestras chamarras son la fusión perfecta de estilo y funcionalidad. Cada diseño está seleccionado 
                    pensando en la versatilidad, desde cortes clásicos de mezclilla hasta modernas chamarras técnicas 
                    resistentes al clima. Utilizamos materiales de primera calidad que no solo se ven bien, sino que están 
                    hechos para durar y protegerte, asegurando que encuentres esa pieza exterior clave que elevará cualquier atuendo.</p>

                <p>Lo que nos diferencia es nuestra obsesión por el ajuste y el detalle. No ofrecemos simplemente ropa, 
                    sino una armadura contra el frío que se siente hecha a tu medida. Con costuras reforzadas, cierres duraderos 
                    y los tejidos más innovadores, cada chamarra está diseñada para ofrecerte la máxima comodidad y rendimiento sin 
                    sacrificar el estilo.</p>
            </div>

             <a href="productos.html">
                <img src="build/img/chamarras/chamarraIndex.webp" alt="Chamarras" loading="lazy">
            </a>

        </section>

         <section class="main-pantalones">
            <a href="productos.html">
                <img src="build/img/pantalones/pantalonIndex.webp" alt="Pantalones" loading="lazy">
            </a>

            <div class="descripcion">

                <a href="productos.html"> <h1>Pantalones</h1> </a>
                
                <p>Encontrar el pantalón perfecto termina aquí. Nuestra colección está construida sobre la base del ajuste ideal,
                   ofreciendo una variedad de cortes que van desde el slim fit más moderno hasta el clásico corte recto. Cada par 
                   está confeccionado con telas de primera calidad, ya sea mezclilla resistente que se amolda a ti o chinos suaves 
                   y transpirables, garantizando comodidad superior y un look impecable durante todo el día.</p>

                <p>No solo vendemos pantalones, vendemos versatilidad y confianza. Lo que nos distingue es la durabilidad: 
                    están hechos para ser la pieza central de tu guardarropa temporada tras temporada. Nos enfocamos en los detalles 
                    que importan, como costuras reforzadas y lavados que perduran, para que puedas moverte con libertad sabiendo que 
                    tu estilo está asegurado, sin importar la ocasión.</p>
            </div>
        </section>

    </main>



    <footer class="footer">
        <p class="copyright">Todos los derechos reservados. Juárez Herrera Erick Adrián &copy; </p>
    </footer>
    <script src="build/js/bundle.min.js"></script>
</body>
</html>
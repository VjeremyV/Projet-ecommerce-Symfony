(
    function () {
        class Carousel {
            /**
             *
             * @param {HTMLElement} element
             * @param {object} options options
             * @param {object} options [options.slidesToScroll=1] Nombres d'élements à faire défiler
             * @param {object} options [options.slidesVisible=1] Nombres d'élements visible dans un slide
             * @param {boolean} options [options.loop=false] Doit on boucler en fin de carousel ? true ou false (false par default)
             */
            constructor(element, options = {}) {
                this.element = element //je peut recuperer cette element a tout moment
                this.options = Object.assign({}, { //si les options ne sont pas present on vas avoir des prolemes donc on fusione cette element avec des options par defaut(on ulitise la méthode asign)
                    slidesToScroll: 1,//propriéte par default
                    slidesVisible: 1,//propriéte par default
                    loop: false //Doit on boucler en fin de carousel ? true ou false (false par default)
                }, options) // on ajoute options si plus tard on veut rajouter des options
                let children = [].slice.call(element.children) // On recupere les enfants HTML a se moment dans l'execution afin d'éviter d'avoir la div carousel qui englobe tout les élement enfants(Convertir Nodelist sous forme de tableaux on utilise la méthode slice puis l'appeler en lui passant en parametre les enfants)
                this.isMobile = false  // Pour la résolution mobile
                this.currentItem = 0 // initialise les item pour la "pagination" du slide
                this.moveCallbacks = [] // création d'un tableau pour parcourir les callbacks avec plus de facilité

                /* Modification du DOM */

                //variable qui sauvegarde les enfants et conserve les element au moment ou le script s'est executer grace a [].slice.call
                //La méthode slice() renvoie un objet tableau, contenant une copie superficielle (shallow copy) d'une portion du tableau d'origine, la portion est définie par un indice de début et un indice de fin (exclus). Le tableau original ne sera pas modifié.
                //La méthode call() réalise un appel à une fonction avec une valeur this donnée et des arguments fournis individuellement.
                this.root = this.createDivWithClass('carousel') //creation d'une div, asigne une class carousel a cette div
                this.container = this.createDivWithClass('carousel__container')//creation d'une div, asigne une class carousel__container a cette div
                this.root.setAttribute('tabindex', '0') //Ajoute un nouvel attribut ou change la valeur d'un attribut existant pour l'élément spécifié. Si l'attribut existe déjà, la valeur est mise à jour ; sinon, un nouvel attribut est ajouté avec le nom et la valeur spécifiés.
                this.root.appendChild(this.container) //on ajoute l'element dans root (dans la div 'carousel')
                this.element.appendChild(this.root) //on rajoute l'element root dans options
                this.items = children.map((child) => {  //fonction qui parcour les  elements enfant et qui les place dans le carousel__container (=> fait reference a la class)
                    let item = this.createDivWithClass('carousel__item') //creer une div avec une class carousel__item dans les enfants
                    item.appendChild(child) //on rajoute les enfants dans la div que l'on vien de creer
                    this.container.appendChild(item) //on ajoute cette item dans le carousel__container
                    return item
                })
                /* Méthode qui met du style au items. Donne les bonne dimentions aux éléments du carousel */
                this.setStyle()
                this.createNavigation()

                //Evenement
                this.moveCallbacks.forEach(cb => cb(0)) // Je veux que tu parcour tout les callbacks et que les appel individuellement. elle passe les calback les un apres les autres en lui passent l'index courant
                this.onWindowResize() //on appelle la fonction des le debut de la page
                window.addEventListener('resize', this.onWindowResize.bind(this)) //évenement qui prend en compte la dimention de la fenetre
                this.root.addEventListener('keyup', evenement => { // Quand on relache la pression de la touche
                    if (evenement.key === 'ArrowRight' || e.key === 'Right') { // Si la touche qui est utiliser est arrowright ou right pour la compatibiliter avec tout les navigateur
                        this.next() // Alors on applique la methode next
                    } else if (evenement.key === 'ArrowLeft' || e.key === 'Left') { // Si la touche qui est utiliser est arrowright Left pour la compatibiliter avec tout les navigateur
                        this.prev() // Alors on applique la methode prev
                    }
                })
            }
            createNavigation() {
                if (document.getElementById("carousel1")){
                let nextButton = this.createDivWithClass('carousel__next') //création du'une div avec l'atribut class :carousel__next
                let prevButton = this.createDivWithClass('carousel__prev') //création du'une div avec l'atribut class :carousel__prev
                this.root.appendChild(nextButton)
                this.root.appendChild(prevButton)
                    /* La méthode bind() crée une nouvelle fonction qui, lorsqu'elle est appelée, a pour contexte this la valeur passée en paramètre et éventuellement une suite d'arguments qui précéderont ceux fournis à l'appel de la fonction créée. */
                    nextButton.addEventListener('click', this.next.bind(this)) //Ajoute un évenement au click de ce button l'évenement est dans la fonction next ci dessous
                    prevButton.addEventListener('click', this.prev.bind(this)) //Ajoute un évenement au click de ce button l'évenement est dans la fonction prev ci dessous
                    if (this.options.loop === true) { // Si l'option loop est strictement = a true alors
                        return
                    }
                    this.onMove(index => { // fonction qui prend en parametre l'index
                        if (index === 0) { // si l'index est strictement egale a 0
                            prevButton.classList.add('carousel__prev--hidden')  // Alors je mes une class au button prev
                        } else { //Sinon tu supprime cette class
                            prevButton.classList.remove('carousel__prev--hidden')
                        }
                        if (this.items[this.currentItem + this.slidesVisible] === undefined) {// si index est superieur ou egal au nombre d'item alors ...  ou  si il y'a un item qui correspond a l'item ciblé + le nbr d'item visible
                            nextButton.classList.add('carousel__next--hidden') // Alors je mes une class au button next
                        } else {
                            nextButton.classList.remove('carousel__next--hidden')//Sinon tu supprime cette class
                        }
                    })
                }

                else {
                    let nextButton = this.createDivWithClass('carousel__next2') //création du'une div avec l'atribut class :carousel__next
                    let prevButton = this.createDivWithClass('carousel__prev2') //création du'une div avec l'atribut class :carousel__prev
                    this.root.appendChild(nextButton)
                    this.root.appendChild(prevButton)
                    /* La méthode bind() crée une nouvelle fonction qui, lorsqu'elle est appelée, a pour contexte this la valeur passée en paramètre et éventuellement une suite d'arguments qui précéderont ceux fournis à l'appel de la fonction créée. */
                    nextButton.addEventListener('click', this.next.bind(this)) //Ajoute un évenement au click de ce button l'évenement est dans la fonction next ci dessous
                    prevButton.addEventListener('click', this.prev.bind(this)) //Ajoute un évenement au click de ce button l'évenement est dans la fonction prev ci dessous
                    if (this.options.loop === true) { // Si l'option loop est strictement = a true alors
                        return
                    }
                    this.onMove(index => { // fonction qui prend en parametre l'index
                        if (index === 0) { // si l'index est strictement egale a 0
                            prevButton.classList.add('carousel__prev--hidden2')  // Alors je mes une class au button prev
                        } else { //Sinon tu supprime cette class
                            prevButton.classList.remove('carousel__prev--hidden2')
                        }
                        if (this.items[this.currentItem + this.slidesVisible] === undefined) {// si index est superieur ou egal au nombre d'item alors ...  ou  si il y'a un item qui correspond a l'item ciblé + le nbr d'item visible
                            nextButton.classList.add('carousel__next--hidden2') // Alors je mes une class au button next
                        } else {
                            nextButton.classList.remove('carousel__next--hidden2')//Sinon tu supprime cette class
                        }
                    })
                }
                }

            setStyle() {
                if (document.getElementById("carousel1")){
                let ratio = this.items.length / this.slidesVisible //on divise le nombre d'éléments avec children.length pas nombre d'élement visible avec options.slidesVisible
                this.container.style.width = (ratio * 100) + "%" // on demande si la largeur du containeur soit egal au ratio * 100 avec la valeur %
                this.items.forEach(item => item.style.width = ((100 / this.slidesVisible) / ratio) + "%") //chaque élement prend 1/3 de la place par rapport au container
                }else{
                    let ratio = this.items.length / this.slidesVisible //on divise le nombre d'éléments avec children.length pas nombre d'élement visible avec options.slidesVisible
                    this.container.style.width = (ratio * 100) + "%" // on demande si la largeur du containeur soit egal au ratio * 100 avec la valeur %
                    this.items.forEach(item => item.style.width = ((80 / this.slidesVisible) / ratio) + "%") //chaque élement prend 1/3 de la place par rapport au container
                }
            }

            next() {
                this.goToItem(this.currentItem + this.slidesToScroll) //Déplace le carousel vers la droite
            }

            prev() {
                this.goToItem(this.currentItem - this.slidesToScroll) //Déplace le carousel vers la gauche
            }
            /**
             * Déplace le carousel vers l'élement ciblé
             * @param {number} index
             */
            goToItem(index) {
                if (index < 0) {//si l'index est inferieur a 0 alors il revien en arriere
                    if (this.options.loop) {
                        index = this.items.length - this.slidesVisible // index est egal au nombre idem - le nombre d'item visible definie en option
                    } else {
                        return
                    }
                } else if (index >= this.items.length || (this.items[this.currentItem + this.slidesVisible] === undefined && index > this.currentItem)) { //Sinon si index est superieur ou egal au nombre d'item alors ...  ou  si il y'a un item qui correspond a l'item ciblé + le nbr d'item visible et l'index doit etre sup a l'index courent
                    if (this.options.loop) {
                        index = 0  // l'index init a 0
                    } else {
                        return
                    }
                }
                let translateX = index * -100 / this.items.length // nombre de l'élement ciblé multiplier par 100 divisé par le nombre d'élements pour calculer translate3d
                this.container.style.transform = 'translate3d(' + translateX + '%, 0, 0)' // Ajoute du style au container (translate3d se calcule en Y X Z )
                this.currentItem = index
                this.moveCallbacks.forEach(cb => cb(index)) // Quand on est sur un slide en particulier est que l'on change l'index alors on parcour tout les callback et on les parcours individuellement
            }
            /**
             * @callback {moveCallbacks}
             * @param {*} cb
             */
            onMove(cb) {  // prend comme parametre un callback, et enregiste c'est callback
                this.moveCallbacks.push(cb)
            }

            onWindowResize() { //definie une dimention mobile si la largeur de la fenetre et inferieur a 800
                let mobile = window.innerWidth < 800  // Valeur de la resolution pour mobile
                if (mobile !== this.isMobile) {// si la valeur de mobile est differente de is mobile (false)
                    this.isMobile = mobile  //alors on change la valeur de la proprieter d'instance
                    this.setStyle() //est on peut redefinire les styles
                    this.moveCallbacks.forEach(cb => cb(this.currentItem)) //Quand on est sur un slide en particulier est que l'on change l'item courant alors on parcour tout les callback et on les parcours individuellement
                }
            }

            /**
             * //creation d'une div avec une class personalisable
             * @param {string} className
             * @returns {HTMLElement}
             */
            createDivWithClass(className) {
                let div = document.createElement('div')
                div.setAttribute('class', className)
                return div
            }

            /** Change le comportement des option quand la resolution de l'écran est a un certain point.
             * @returns {number}
             */
            get slidesToScroll() { //si on est sur mobile alors 1 (true) sinon on retourne l'option
                return this.isMobile ? 1 : this.options.slidesToScroll
            }

            /** Change le comportement des option quand la resolution de l'écran est a un certain point.
             * @returns {number}
             */
            get slidesVisible() { //si on est sur mobile alors 1 (true) sinon on retourne l'option
                return this.isMobile ? 1 : this.options.slidesVisible
            }

        }

        document.addEventListener('DOMContentLoaded', function () {
            new Carousel(document.querySelector('#carousel1'), {
                slidesToScroll: 1, // Combien de slide on scroll dans le carousel
                slidesVisible: 3, // slide visible dans le carousel
                loop: true  //boucle en fin de carousel
            })
        })
        document.addEventListener('DOMContentLoaded', function () {
            new Carousel(document.querySelector('#carousel2'), {
                slidesToScroll: 2, // Combien de slide on scroll dans le carousel
                slidesVisible: 2, // slide visible dans le carousel
                loop: true  //boucle en fin de carousel
            })
        })

    }
)()

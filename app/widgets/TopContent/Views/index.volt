<div class="newsfeed-wrapper">
    {% if params['showTitle'] %}
        <h3 class="title">{{ params['title'] }}</h3>
    {% endif %}
    <div class="news-container container">
        <div class="news-wrapper">
            {% for item in model %}
                {% set images = item.content.getImagesArray() %}
                <div class="news-box">
                    <a class="wrap" href="{{ url(['for': 'article', 'articleAlias': item.content.alias]) }}">
                        {% if images and images.introImage %}
                        <div class="news-image">
                            <div class="bg-image" style="background-image: url('{{ images.introImage }}')"></div>
                        </div>
                        {% endif %}
                        <div class="text-wrapper">
                            <h3>{{ item.content.title }}</h3>

                            <div class="news-content">
                                {{ helper.htmlDecode(item.content.introtext) }}
                            </div>
                        </div>
                    </a>
                </div>
            {% endfor %}
        </div>
    </div>
    <h4 class="title"><a href="{{ url(['for': 'articles']) }}">SEE ALL ARTICLES</a></h4>
</div>